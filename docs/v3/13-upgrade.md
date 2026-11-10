# Upgrade Guide

## Upgrading to 3.0 from 2.x

### Requirements

- PHP 8.3 or newer, including `ext-intl` and `ext-json`.
- Laravel 12 or 13.
- `mollie/laravel-mollie` 5.x and `mollie/mollie-api-php` 4.x.
- Dompdf 3.1 or newer, Money 4.6 or newer, and Carbon 3.8 or newer.

Applications that explicitly require older versions of these packages must update those constraints too.

### Deployment

1. Back up your database. Pause `cashier:run` and drain in-flight webhook requests while deploying the code and schema together.
2. Update dependencies:

   ```bash
   composer require mollie/laravel-cashier-mollie:^3.0 --with-all-dependencies
   ```

3. Run `php artisan cashier:update` once to publish the v2-to-v3 migration. Inspect the generated migration, especially if you customize Cashier table names or already added metadata columns. Do not publish the fresh-install migrations over existing tables.
4. Run `php artisan migrate --force` in production. The migration adds nullable `metadata` fields to Cashier tables and `cancellation_reason` to subscriptions; existing subscriptions and payment IDs are preserved.
5. Clear cached configuration, deploy the matching configuration changes, and restart queue workers. Resume the scheduler and webhook processing after the migration succeeds.
6. Test a first payment, a renewal, a late payment after cancellation, and a refund using a Mollie test API key before upgrading production.

For a release candidate, use its explicit version (for example `3.0.0-rc.1`) instead of `^3.0`.

### SDK enums and typed values

SDK constants under `Mollie\Api\Types` are string-backed enums. Update any application code, listeners, configuration, or fixtures that use the old uppercase constants:

```php
// Before
PaymentStatus::PAID;
SequenceType::FIRST;

// After
PaymentStatus::Paid;
SequenceType::First;
```

SDK payment statuses are enum cases, with raw strings retained for unknown API values. Cashier's database status columns remain strings. Use `PaymentStatus::Paid->value` when comparing a Cashier model's `mollie_payment_status`, and `mollie_enum_value($payment->status)` when normalizing an SDK resource.

SDK money fields are readonly `Mollie\Api\Http\Data\Money` values. Replace `stdClass` money fixtures with `Money::fromArray(['currency' => 'EUR', 'value' => '10.00'])`. Existing reads of `->currency` and `->value` continue to work. See the [SDK upgrade guide](https://github.com/mollie/mollie-api-php/blob/v4.1.0/UPGRADING.md) for additional changes affecting code that calls Mollie directly.

### Cancellation and late payments

`cancel()`, `cancelAt()` and `cancelNow()` record `merchant_requested` by default. A late paid or failed webhook does not reverse that cancellation or replace its reason. Explicitly call `resume()` during the grace period to resume the subscription.

A recovered payment on a subscription cancelled for `payment_failed` resumes billing at a future plan boundary, rather than scheduling a duplicate charge at the old cancellation timestamp. Automatic recovery and explicit resumption clear `cancellation_reason`.

Existing cancellations have a null reason. Their automatic recovery keeps the legacy behavior, with a future billing boundary. If you know that an old cancellation was explicitly requested, backfill `merchant_requested` before accepting late webhooks; Cashier cannot infer historical intent.

### Refunds and credit

Refunds return the Mollie-funded amount first, then restore used credit when the refund is processed. Follow-up refunds can complete locally once the Mollie-funded amount is exhausted. Fully credit-funded orders still cannot be refunded through this API.

`mollie_refund_id` can now contain a synthetic `local_…` ID. Check `$refund->isLocal()` before passing an ID to Mollie. The refund and its credit invoice continue to cover the full reversed order value.

Only one outstanding refund per order is permitted. Wait until it is processed or fails before initiating another. Builders re-read the order under a database lock, reject amounts above the remaining order value, and handle queued and processing refunds through aftercare. A cancelled Mollie refund is handled as a failed local refund.

The upgrade does not retroactively restore credit for refunds processed on v2. Reconcile those historical refunds separately if needed.

### Other v3 changes

Cashier models expose a nullable JSON-cast `metadata` field. If a custom model uses that name for another purpose, migrate it before upgrading. `Subscription::valid()` delegates to `active()`; the `Laravel\Cashier` namespace and existing checkout response classes remain available.

Retain configured plans while subscriptions still reference them. Removing a plan does not migrate or cancel those subscriptions automatically.
