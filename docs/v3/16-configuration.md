# Configuration

You have the possibility to make certain configurations (optional).

## Custom Models

You can extend the models used internally by Cashier by defining your own model and extending the corresponding Cashier model.

```php
namespace App\Models\Cashier;

use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    protected $table = 'subscriptions';
}

```

This could be useful, if you want custom behavior or use other table names than the default ones to avoid conflict with existing tables.

Configurable models are:

- `Laravel\Cashier\Subscription`
- `Laravel\Cashier\Order\Order`
- `Laravel\Cashier\Order\OrderItem`
- `Laravel\Cashier\Coupon\AppliedCoupon`
- `Laravel\Cashier\Coupon\RedeemedCoupon`
- `Laravel\Cashier\Credit\Credit`
- `Laravel\Cashier\Refunds\Refund`
- `Laravel\Cashier\Refunds\RefundItem`
- `Laravel\Cashier\Payment`

After defining your model, you may instruct Cashier to use your custom model via the `Laravel\Cashier\Cashier` class. Typically, you should inform Cashier about your custom models in the boot method of your application's `App\Providers\AppServiceProvider` class:


```php
use App\Models\Cashier\Subscription;

/**
* Bootstrap any application services.
*
* @return void
*/
public function boot()
{
    Cashier::useSubscriptionModel(Subscription::class);
    // ... use the same pattern for additional models
}
```
