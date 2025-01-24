# Mollie webhook event handling

Cashier Mollie has already wired up the default Mollie webhook handling for you.

A failed recurring charge will result in the subscription being cancelled directly. 

Additionally, listen for the following events (in the `Laravel\Cashier\Events` namespace) to add app specific behaviour:

- `OrderPaymentPaid` and `OrderPaymentFailed`
- `FirstPaymentPaid` and `FirstPaymentFailed`
