# Metered billing

Some business cases will require dynamic subscription amounts.

To allow for full flexibility Cashier Mollie allows you to define your own set of Subscription OrderItem preprocessors.
These preprocessors are invoked when the OrderItem is due, right before being processed into a Mollie payment.

If you're using metered billing, this is a convenient place to calculate the amount based on the usage statistics and
reset any counters for the next billing cycle.

You can define the preprocessors in the `cashier_plans` config file.
