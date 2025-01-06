# Handling failed payments

Cashier automatically handles failed payments for you. In case the payment was for a subscription, the subscription gets cancelled immediately.

For the best customer experience, listen for the `OrderPaymentFailed` and `OrderPaymentFailedDueToInvalidMandate` events to notify the user and ask to [update the payment method](06-customer.html#updating-customer-payment-method) before retrying the payment.

Here's how to retry a payment:

```php
use App\Models\User;

$user = App\User::find(1);

$order = $user->orders->find(1) // id of the failed order;
$order->retryNow();
```

## Subscriptions and failed payments

Subscriptions are active until Cashier gets notified by Mollie that a payment related to that subscription has failed. On a successful payment retry, the subscription will be reactivated, continuing the billing cycle as it was before.
