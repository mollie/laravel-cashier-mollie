# Upgrade Guide

## Upgrading To 3.0 From 2.x

To pull Laravel Cashier Mollie v3

```bash
composer require mollie/laravel-cashier-mollie:^3.0
```
 
Once you have pulled in the package:

1. Run `php artisan cashier:update`. This will publish the required migrations to your database.

2. Now run the migrations `php artisan migrate`.
