# Mollie webhook event handling

Cashier Mollie has already wired up the default Mollie webhook handling for you.

A failed recurring charge will result in the subscription being cancelled directly. 

If Cashier cannot fetch a payment from Mollie because of an API error (such as a 5xx or 429), the webhook returns an error so Mollie can retry. Unknown payment IDs receive `200 OK` when debugging is disabled.

Additionally, listen for the following events (in the `Laravel\Cashier\Events` namespace) to add app specific behaviour:

- `OrderPaymentPaid` and `OrderPaymentFailed`
- `FirstPaymentPaid` and `FirstPaymentFailed`
