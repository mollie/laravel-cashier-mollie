# F.A.Q.

## Ok. So how does this subscription magic actually work?

This Cashier implementation schedules triggering payments from the client side, instead of relying on subscription management at Mollie.
And yes, Mollie also offers a Subscription API, but it does not support all the niceties you've come to expect from Cashier,
so this package provides its own subscription engine.

From a high level perspective, this is what the process looks like:

1. A `Subscription` is created using the `MandatePaymentSubscriptionBuilder` (redirecting to Mollie's checkout to create
   a `Mandate`) or `PremandatedSubscriptionBuilder` (using an existing `Mandate`).
2. The `Subscription` yields a scheduled `OrderItem` at the beginning of each billing cycle.
3. `OrderItems` which are due are preprocessed and bundled into `Orders` whenever possible by a scheduled job (i.e.
   daily). This is done, so your customer will receive a single payment/invoice for multiple items later on in the chain).
   Preprocessing the `OrderItems` may involve applying dynamic discounts or metered billing, depending on your
   configuration.
4. The `Order` is processed by the same scheduled job into a payment:
    - First, (if available) the customer's balance is processed in the `Order`.
    - If the total due is positive, a Mollie payment is incurred.
    - If the total due is 0, nothing happens.
    - If the total due is negative, the amount is added to the user's balance. If the user has no active subscriptions left, the `BalanceTurnedStale` event will be raised.
5. You can generate an `Invoice` (html/pdf) for the user.

## My billable model uses UUIDs, how can I get Cashier Mollie to work with this?
By default Cashier Mollie uses `unsignedInteger` fields for the billable model relationships.
If required for your billable model, modify the cashier migrations for UUIDs:

```php
// Replace this:
$table->unsignedInteger('owner_id');

// By this:
$table->uuid('owner_id');
```
And in your `Billable` model add
```php
protected $keyType = 'string';
```
## How is prorating handled?

Cashier Mollie applies prorating by default. With prorating, customers are billed at the start of each billing cycle.

This means that when the subscription quantity is updated or is switched to another plan:

1. the billing cycle is reset
2. the customer is credited for unused time, meaning that the amount that was overpaid is added to the customer's balance.
3. a new billing cycle is started with the new subscription settings. An Order (and payment) is generated to deal with
   all the previous, including applying the credited balance to the Order.

This does not apply to the `$subscription->swapNextCycle('other-plan')`, which simply waits for the next billing cycle
to update the subscription plan. A common use case for this is downgrading the plan at the end of the billing cycle.

## How can I load coupons and/or plans from the database?

Because Cashier Mollie uses contracts a lot it's quite easy to extend Cashier Mollie and use your own implementations.
You can load coupons/plans from database, a file or even a JSON API.

For example a simple implementation of plans from the database:

Firstly you create your own implementation of the plan repository and implement `Laravel\Cashier\Plan\Contracts\PlanRepository`.
Implement the methods according to your needs and make sure you'll return a `Laravel\Cashier\Plan\Contracts\Plan`.

```php
use App\Models\Plan;
use Laravel\Cashier\Exceptions\PlanNotFoundException;
use Laravel\Cashier\Plan\Contracts\Plan as PlanContract;
use Laravel\Cashier\Plan\Contracts\PlanRepository;

class DatabasePlanRepository implements PlanRepository
{
    public static function find(string $name): PlanContract
    {
        /** @var Plan $plan */
        $plan = Plan::where('name', $name)->firstOrFail();

        // Return a \Laravel\Cashier\Plan\Plan by creating one from the database values
        return $plan->buildCashierPlan();

        // Or if your model implements the contract: \Laravel\Cashier\Plan\Contracts\Plan
        return $plan;
    }

    public static function findOrFail(string $name): PlanContract
    {
        if (($result = self::find($name)) === null) {
            throw new PlanNotFoundException;
        }

        return $result;
    }
}
```
::: details Example Plan model (app/Plan.php) with buildCashierPlan and returns a \Laravel\Cashier\Plan\Plan
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Plan\Plan as CashierPlan;
use Money\Currency;
use Money\Money;
use Laravel\Cashier\Order\OrderItemPreprocessorCollection as Preprocessors;

class Plan extends Model
{
    use HasFactory;

    /**
     * Builds a Cashier plan from the current model.
     *
     * @returns \Laravel\Cashier\Plan\Plan
     */
    public function buildCashierPlan(): CashierPlan
    {
        $plan = new CashierPlan($this->name);

        return $plan->setAmount($this->getAmount())
            ->setInterval($this->interval)
            ->setDescription($this->description)
            ->setFirstPaymentMethod(config('cashier.first_payment.method'))
            ->setFirstPaymentAmount($this->getAmount())
            ->setFirstPaymentDescription($this->description)
            ->setFirstPaymentRedirectUrl(route('plans-payment.success', ['plan' => $this->id]))
            ->setFirstPaymentWebhookUrl(Cashier::firstPaymentWebhookUrl())
            ->setOrderItemPreprocessors(Preprocessors::fromArray(config('cashier_plans.defaults.order_item_preprocessors')));
    }

    private function getAmount(): Money
    {
        return new Money($this->price, new Currency($this->currency));
    }
}
```

The DB-schema for the above Model could look like the following.

```php
Schema::create('plans', function (Blueprint $table) {
	$table->id();
	$table->string('name');
	$table->string('description');
	$table->string('price');
	$table->string('currency');
	$table->string('interval');
	$table->timestamps();
});
```

You can also create a `PlanFactory`.

```php
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'price' => '10.00',
            'currency' => $this->faker->currencyCode,
            'interval' => $this->faker->randomElement(['month', 'year']),
        ];
    }
}
```
:::

Then you have to bind your implementation to the Laravel/Illuminate container by registering the binding in a service provider

```php
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(\Laravel\Cashier\Plan\Contracts\PlanRepository::class, DatabasePlanRepository::class);
    }
}
```

In order to test if your implementation is setup correctly, you can run the following code snippet.
```php
use Laravel\Cashier\Plan\Contracts\PlanRepository;
use App\Models\Plan;

Plan::factory()->create(["name" => "test"]);
resolve(PlanRepository::class)->find("test");
```

---

Cashier Mollie will now use your implementation of the PlanRepository. For coupons this is basically the same,
make sure you implement the CouponRepository contract and bind the contract to your own implementation.

## I have enabled iDEAL and/or Bancontact on my Mollie dashboard but the checkout only lists creditcard. Why?

Ensure to also enable `directdebit` on your Mollie dashboard if you want to allow iDEAL and/or Bancontact on your checkout.
