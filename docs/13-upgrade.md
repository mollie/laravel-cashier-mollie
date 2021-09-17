# Upgrade Guide

## Upgrading To 2.0 From 1.x

**Switch to the alpha testing repository**

Remove the `laravel/cashier-mollie` dependency from your `composer.json`.

Then add this custom repository:

```json
"repositories": [
    {
      "type": "vcs",
      "url":  "git@github.com:sandorianHQ/laravel-cashier-mollie.git"
    }
]
```

Now pull the alpha testing package in using composer:

```bash
composer require mollie/laravel-cashier-mollie "^0.2.0"
```

Once you have pulled in the package:

1. Run `php artisan cashier:update`. If you want to put the application in maintenance mode, lock cashier's webhooks and lock `cashier:run`, then use: `php artisan cashier:update --maintenance`.

2. If you're using `AddGenericOrderItem` or `AddBalance`, you'll need to update the `create()` calls.

- Find `AddGenericOrderItem::create([...])` usage and add `$quantity`. The new constructor signature is
```php
public function __construct(Model $owner, Money $unitPrice, int $quantity, string $description, int $roundingMode = Money::ROUND_HALF_UP) {...}
```
- Find `AddBalance::create([...])` usage and add `$quantity`. The new constructor signature is
```php
public function __construct(Model $owner, Money $subtotal, int $quantity, string $description) {...}
```
3. Once you have asserted everything went ok, you can remove the `cashier_backup_orders` table.
