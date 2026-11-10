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

## Refunding orders that used credit

An order can be paid partly (or entirely) from the owner's credit balance. In that case only
`total_due` is actually charged through Mollie, and that is the most Mollie is able to refund.

Cashier settles refunds against the Mollie payment first and against the credit used last. A
refund is capped at whatever is still refundable through Mollie, and anything it reverses beyond
that is returned to the owner's credit balance:

```php
// Order total EUR 22.00, of which EUR 5.00 was paid using credit and EUR 17.00 through Mollie.
$order->refundCompletely();
// -> EUR 17.00 is charged back through Mollie
// -> EUR 5.00 is returned to the owner's credit balance
```

The refund still reverses the full order value, so the credit order and invoice cover the
complete order.

The credit is returned when a refund is processed. When a refund includes a Mollie amount, this
happens after Mollie reports it as processed. A credit-only follow-up refund, after the Mollie
amount has already been exhausted by an earlier refund, is processed immediately without another
Mollie API call. A refund that ends up `failed` moves no credit at all, so a failed refund can never
leave credit behind for a refund that did not happen. Successive partial refunds return credit once,
as each processed refund reverses more than was charged through Mollie.

A refund can never reverse more order value than the order has left, however it is funded: the
refund items must total at most `total - amount_refunded`. Reversing more would push
`amount_refunded` past the order total and generate credit orders worth more than the original
order, so it throws a `LogicException`. On an order that has been partially refunded already,
build a refund for the remaining items rather than calling `refundCompletely()`.

An order that was paid entirely using credit, or that has already been refunded in full, has
nothing left to charge back through Mollie and will throw a `LogicException`.

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
