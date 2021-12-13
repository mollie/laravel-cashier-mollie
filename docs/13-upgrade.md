# Upgrade Guide

## Preparations

If you're using `receipt_old.blade.php`, make sure to either publish it to your views directory before upgrading, or switch to the default `receipt.blade.php`.

## Upgrading To 2.0 From 1.x

Remove the `laravel/cashier-mollie` dependency from your `composer.json`.

Once you have removed  the `laravel/cashier-mollie`, you may install Laravel Cashier Mollie using Composer:

```bash
composer require mollie/laravel-cashier-mollie "^2.0"
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

3. Run the migrations `php artisan migrate`.
