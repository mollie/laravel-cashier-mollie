# Early release installation

## Warning

This is an early release for this package. Things are likely to change before production-ready stability is reached.

At this point it's strongly advised to only use this package with Mollie's **test API**.

The more we learn, the faster we will get to a stable release. Help us get there faster by opening a ticket in the issue
tracker with your comments, suggestions, questions, problems etc.. We're here to help you.

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
composer require mollie/laravel-cashier-mollie "^0.1.0"
```
