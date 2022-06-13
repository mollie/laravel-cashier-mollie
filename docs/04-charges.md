# One-time charges

If you would like to make a one-time charge against a customer, you may use the `charge` method on a billable model
instance.

Leveraging Mollie's [recurring features](https://docs.mollie.com/payments/recurring), Cashier's one-time charge features
work similar to the subscription features:

1. Build the charge by adding some items.
2. The customer either gets billed using an existing mandate, or gets redirected to Mollie's checkout to perform a first
   payment. This will result in a new mandate.
3. Once the payment has been received in your Mollie account, Cashier Mollie will generate an Order.

```php
use App\Models\User;

$user = App\User::find(1);

$item = new \Laravel\Cashier\Charge\ChargeItemBuilder($user);
$item->unitPrice(money(100,'EUR')); //1 EUR
$item->description('Test Item 1');
$chargeItem = $item->make();

$item2 = new \Laravel\Cashier\Charge\ChargeItemBuilder($user);
$item2->unitPrice(money(200,'EUR'));
$item2->description('Test Item 2');
$chargeItem2 = $item2->make();

$result = $user->newCharge()
    ->addItem($chargeItem)
    ->addItem($chargeItem2)
    ->setRedirectUrl('https://www.example.com')
    ->create();

if(is_a($result, \Laravel\Cashier\Http\RedirectToCheckoutResponse::class)) {
    return $result;
}

return back()->with('status', 'Thank you.');
```
