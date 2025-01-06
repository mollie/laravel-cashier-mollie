# Invoices

Listen for the `OrderInvoiceAvailable` event (in the `Laravel\Cashier\Events` namespace).
When a new order has been processed, you can grab the invoice by:

```php
$invoice = $event->order->invoice();
$invoice->view(); // get a Blade view
$invoice->pdf(); // get a pdf of the Blade view
$invoice->download(); // get a download response for the pdf
```

To list invoices, access the user's orders using: `$user->orders->invoices()`.
This includes invoices for all orders, even unprocessed or failed orders.

For example:

```php
// Your blade view file
<ul class="list-unstyled">
    @foreach(auth()->user()->orders as $order)
    <li>
        
        <a href="/download-invoice/{{ $order->id }}">
            {{ $order->invoice()->id() }} -  {{ $order->invoice()->date() }}
        </a>
    </li>
    @endforeach
</ul>

// routes/web.php
Route::middleware('auth')->get('/download-invoice/{orderId}', function($orderId){

    return (request()->user()->downloadInvoice($orderId));
});
```

## Finding a specific invoice

### Find invoice by order id

It's possible to find a specific invoice by its order number.

```php
$order = $user->orders()->first();

$user->findInvoice($order->number);
```

You can also retrieve the invoice using the order id:

```php
$user->findInvoiceByOrderId($order->id);
```


::: tip
If the invoice is not associated with the user you're searching for, it will throw a
```\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException```
:::

### findInvoiceOrFail

If you wish to show a 404 error page whenever the invoice is not found, you may use the `findInvoiceOrFail` and
`findInvoiceByOrderIdOrFail` methods on your billable model.

```php
$order = $user->orders()->first();

$user->findInvoiceOrFail($order->number);
$user->findInvoiceByOrderIdOrFail($order->id);
```

If the invoice cannot be found, a

```php
\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
```

will be thrown.

If the invoice doesn't belong to the user, it will throw a 

```php
\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
```

In a standard Laravel application those exceptions will be turned in a proper 404 or respectively 403 HTTP response.
