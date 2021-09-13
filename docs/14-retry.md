# Retry

In case a mandated payment fails, you can ask the customer to [update the payment method](06-customer.html#updating-customer-payment-method) and then retry the payment.
For retrying the payment, use the `retryNow()`  method on the failed Order.
---
The retryNow() method can only be used for order that have failed payment status
Subscriptions and failed payments:
Subscriptions are active until Cashier gets notified by Mollie that a payment related to that subscription has failed. On a successful payment retry, the subscription will be reactivated, continuing the billing cycle as it was before.
---
```php
use App\Models\User;

$user = App\User::find(1);

$order = $user->orders->find(1) // id of the failed order;
$order->retryNow();
```
