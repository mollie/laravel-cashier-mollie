# Early release installation

::: warning
This is an early release for this package. Things are likely to change before production-ready stability is reached.

At this point it's strongly advised to only use this package with Mollie's **test API**.

The more we learn, the faster we will get to a stable release. Help us get there faster by opening a ticket in the issue
tracker with your comments, suggestions, questions, problems etc.. We're here to help you.
:::

## Installation

First, make sure to add the Mollie key to your `.env` file. You can obtain an API key from the [Mollie dashboard](https://www.mollie.com/dashboard/developers/api-keys):

```dotenv
MOLLIE_KEY="test_xxxxxxxxxxx"
```

Next, add the repository to your `composer.json`:

```json
"repositories": [
    {
    "type": "vcs",
    "url":  "git@github.com:sandorianHQ/laravel-cashier-mollie.git"
    }
]
```

Now pull the package in using composer:

```bash
composer require mollie/laravel-cashier-mollie "^0.2.0"
```

## Setup

Once you have pulled in the package:

1. Run `php artisan cashier:install`.

2. Add these fields to your billable model's migration (typically the default "create_user_table" migration):

    ```php
    $table->string('mollie_customer_id')->nullable();
    $table->string('mollie_mandate_id')->nullable();
    $table->decimal('tax_percentage', 6, 4)->default(0); // optional
    $table->dateTime('trial_ends_at')->nullable(); // optional
    $table->text('extra_billing_information')->nullable(); // optional
    ```

3. Run the migrations: `php artisan migrate`

4. Prepare the configuration files:

    - configure at least one subscription plan in `config/cashier_plans.php`.

    - in `config/cashier_coupons.php` you can manage any coupons. By default an example coupon is enabled. Consider
      disabling it before deploying to production.

    - the base configuration is in `config/cashier`. Be careful while modifying this, in most cases you will not need
      to.

5. Prepare the billable model (typically the default Laravel User model):

    - Add the `Laravel\Cashier\Billable` trait.

    - Optionally, override the method `mollieCustomerFields()` to configure what billable model fields are stored while creating the Mollie Customer.
      Out of the box the `mollieCustomerFields()` method uses the default Laravel User model fields:

    ```php
    public function mollieCustomerFields() {
        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
    ```
   Learn more about storing data on the Mollie Customer [here](https://docs.mollie.com/reference/v2/customers-api/create-customer#parameters).

    - Implement
    ```php
    Laravel\Cashier\Order\Contracts\ProvidesInvoiceInformation
    ```
interface. For example:

    ```php
    /**
    * Get the receiver information for the invoice.
    * Typically includes the name and some sort of (E-mail/physical) address.
    *
    * @return array An array of strings
    */
    public function getInvoiceInformation()
    {
        return [$this->name, $this->email];
    }
    
    /**
    * Get additional information to be displayed on the invoice. Typically a note provided by the customer.
    *
    * @return string|null
    */
    public function getExtraBillingInformation()
    {
        return null;
    }
    ```

6. Schedule a periodic job to execute `Cashier::run()`.

    ```php
    $schedule->command('cashier:run')
        ->daily() // run as often as you like (Daily, monthly, every minute, ...)
        ->withoutOverlapping(); // make sure to include this
    ```

You can find more about scheduling jobs using Laravel [here](https://laravel.com/docs/scheduling).

🎉 You're now good to go :).