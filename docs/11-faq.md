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
use App\Plan;
use Laravel\Cashier\Exceptions\PlanNotFoundException;
use Laravel\Cashier\Plan\Contracts\PlanRepository;

class DatabasePlanRepository implements PlanRepository
{
    public static function find(string $name)
    {
        $plan = Plan::where('name', $name)->first();

        if (is_null($plan)) {
            return null;
        }

        // Return a \Laravel\Cashier\Plan\Plan by creating one from the database values
        return $plan->buildCashierPlan();

        // Or if your model implements the contract: \Laravel\Cashier\Plan\Contracts\Plan
        return $plan;
    }

    public static function findOrFail(string $name)
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
<?php

namespace App;

use Laravel\Cashier\Plan\Plan as CashierPlan;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    /**
     * Builds a Cashier plan from the current model.
     *
     * @returns \Laravel\Cashier\Plan\Plan
     */
    public function buildCashierPlan(): CashierPlan
    {
        $plan = new CashierPlan($this->name);
        
        return $plan->setAmount(mollie_array_to_money($this->amount))
            ->setInterval($this->interval)
            ->setDescription($this->description)
            ->setFirstPaymentMethod($this->first_payment_method)
            ->setFirstPaymentAmount(mollie_array_to_money($this->first_payment_amount))
            ->setFirstPaymentDescription($this->first_payment_description)
            ->setFirstPaymentRedirectUrl($this->first_payment_redirect_url)
            ->setFirstPaymentWebhookUrl($this->first_payment_webhook_url)
            ->setOrderItemPreprocessors(Preprocessors::fromArray($this->order_item_preprocessors));
    }
}
```

Note: In this case you'll need to add accessors for all the values (like amount, interval, fist_payment_method etc.)
to make sure you'll use the values from your defaults (config/cashier_plans.php > defaults).
:::

Then you just have to bind your implementation to the Laravel/Illuminate container by registering the binding in a service provider

```php
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(\Laravel\Cashier\Plan\Contracts\PlanRepository::class, DatabasePlanRepository::class);
    }
}
```

Cashier Mollie will now use your implementation of the PlanRepository. For coupons this is basically the same,
just make sure you implement the CouponRepository contract and bind the contract to your own implementation.
