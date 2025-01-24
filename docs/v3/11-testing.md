# Testing

Cashier Mollie's test suite is running phpunit. Most tests run locally, without interacting with the Mollie API.

Only the integration tests in `/tests/Mollie` require the Mollie API to be available and prepared.
For your convenience, this test group is disabled by default.

The default set of tests can be run using:

```bash
composer test
```

## Enable Mollie integration tests

In order to run the integration tests against Mollie's API, you'll need to prepare some test resources
in your Mollie account, and register these in your phpunit config file.

Start by copying `phpunit.xml.dist` into `phpunit.xml`.

Then, remove the `mollie_integration` from the excluded groups in `phpunit.xml`.

Finally, prepare the following test resources in your Mollie account and register the related environment variables in `phpunit.xml`:

**Mollie API test key**
You can obtain this key from the dashboard right after signing up.

```xml
<env name="MOLLIE_KEY" value="YOUR_VALUE_HERE"/>
```

**ID of a customer with a valid directdebit mandate**
```xml
<env name="MANDATED_CUSTOMER_DIRECTDEBIT" value="YOUR_VALUE_HERE"/>
```

**Mandate's ID (of the previously mentioned customer)**
```xml
<env name="MANDATED_CUSTOMER_DIRECTDEBIT_MANDATE_ID" value="YOUR_VALUE_HERE"/>
```

**ID of a successful ("paid) payment by the customer**
Use a 1000 EUR amount.
```xml
<env name="PAYMENT_PAID_ID" value="YOUR_VALUE_HERE"/>
```

**ID of an unsuccessful ("failed") payment by the customer**
```xml
<env name="PAYMENT_FAILED_ID" value="YOUR_VALUE_HERE"/>
```

Now you can run:

``` bash
composer test
```
