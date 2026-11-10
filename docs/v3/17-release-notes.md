# Version 3 release candidate

Version 3 targets PHP 8.3+, Laravel 12 and 13, laravel-mollie 5, and Mollie SDK 4. Read the [upgrade guide](./13-upgrade.md) before updating an existing installation.

## Included

- SDK enum and typed money compatibility ([#332](https://github.com/mollie/laravel-cashier-mollie/pull/332)). Cashier database status values remain strings.
- Webhook API failures return errors so Mollie can retry ([#331](https://github.com/mollie/laravel-cashier-mollie/pull/331)). Unknown payment IDs continue to receive a 200 response when debugging is disabled.
- Refunds account for credit used on the original order ([#329](https://github.com/mollie/laravel-cashier-mollie/pull/329)). Credit-only follow-ups can finish locally, and `Refund::isLocal()` distinguishes their synthetic IDs. Refund initiation is serialized per order; outstanding refunds must finish before another is requested.
- Queued and processing refunds are included in aftercare processing. Cancelled Mollie refunds are treated as failed local refunds.
- Merchant-requested cancellations survive late payment and failure webhooks ([#293](https://github.com/mollie/laravel-cashier-mollie/issues/293)). Recovery from payment failure schedules a future renewal, preserves an ongoing trial, and clears the cancellation reason.
- Nullable JSON-cast metadata on Cashier models and a subscription cancellation reason, with an upgrade migration for existing v2 installations.
- Corrected production confirmation in `cashier:update`.

## Deferred

Next-cycle quantity changes ([#250](https://github.com/mollie/laravel-cashier-mollie/pull/250)) need an upgrade migration and a decision about pending plan changes ([#264](https://github.com/mollie/laravel-cashier-mollie/issues/264)). They are not included in this candidate.

Event logging ([#285](https://github.com/mollie/laravel-cashier-mollie/pull/285)) remains unfinished and is not included. Namespace changes, a new documentation framework, and checkout response class renaming are also outside this release.

Internationalized email domain normalization ([#314](https://github.com/mollie/laravel-cashier-mollie/issues/314)) remains open. Applications must normalize Unicode email domains to Punycode before sending them to Mollie.

Keep plan definitions while subscriptions reference them ([#301](https://github.com/mollie/laravel-cashier-mollie/issues/301)). Removing a plan is not a supported way to terminate subscriptions.

The PHP 8.4 nullable parameter issue ([#305](https://github.com/mollie/laravel-cashier-mollie/issues/305)) is already fixed. Scheduled order items ([#266](https://github.com/mollie/laravel-cashier-mollie/issues/266)) are available through `$billable->orderItems()->scheduled()`.
