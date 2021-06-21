<h1 align="center">Subscription billing with Laravel Cashier for Mollie</h1>
<p align="center">
  <img src="https://sandorianhq.github.io/laravel-cashier-mollie/assets/img/laravelcashiermollie.a7bde0e4.jpg"/>
</p>


<img src="https://info.mollie.com/hubfs/github/laravel-cashier/editorLaravel.jpg" />

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sandorianHQ/laravel-cashier-mollie.svg?style=flat-square)](https://packagist.org/packages/sandorianHQ/laravel-cashier-mollie)
[![Github Actions](https://github.com/sandorianHQ/laravel-cashier-mollie/workflows/tests/badge.svg)](https://github.com/sandorianHQ/laravel-cashier-mollie/actions)

Laravel Cashier provides an expressive, fluent interface to subscriptions using [Mollie](https://www.mollie.com)'s billing services.

## Early release warning

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

## Official Documentation
Documentation can be found on the [Laravel Cashier Mollie website](https://sandorianhq.github.io/laravel-cashier-mollie/).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email support@mollie.com instead of using the issue tracker.

## Credits

- [Mollie.com](https://www.mollie.com)
- [Sander van Hooft](https://github.com/sandervanhooft)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
