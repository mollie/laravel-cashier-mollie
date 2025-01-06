# Refunds

Cashier offers full support for refunds. Refunds can only be performed against _paid_ Orders. 

## Performing a complete refund

If you need to refund an order completely, invoke the `refundCompletely` method on its instance:

```php
use App\Models\User;

$user = App\User::find(1);

$order = $user->orders->first();
$order->refundCompletely();
```

## Performing advanced refunds

For a finer grained control, build a refund manually:

```php
use Laravel\Cashier\Refunds\RefundItem;

$shippingCosts = RefundItem::make([
    'owner' => $user,
    'description' => 'Shipping costs',
    'currency' => 'EUR',
    'quantity' => 1,
    'unit_price' => 667, // EUR 6.67
    'tax_percentage' => 21,
]);

$order->newRefund()
    ->addItem(RefundItem::makeFromOrderItem($order->items->first()))
    ->addItem($shippingCosts)
    ->create();
```

## Credit orders and invoices

When Mollie has paid out the refund to the customer, Cashier dispatches the `RefundProcessed` event.

From this event you can get the automatically generated credit order and the matching invoice receipt:

```php
$creditOrder = $refundProcessedEvent->refund->order;
$invoice = $creditOrder->invoice();
$invoice->view(); // get a Blade view
$invoice->pdf(); // get a pdf of the Blade view
$invoice->download(); // get a download response for the pdf
```

Note that the credit Order and the invoice are a regular Cashier order and invoice. This means these are included when listing the billable orders and invoices:

```php
$user->orders;
$user->orders->invoices(); // includes credit invoices
```
