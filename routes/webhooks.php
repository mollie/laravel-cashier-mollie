<?php

use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Http\Controllers\AftercareWebhookController;
use Laravel\Cashier\Http\Controllers\FirstPaymentWebhookController;
use Laravel\Cashier\Http\Controllers\WebhookController;

Route::namespace('\Laravel\Cashier\Http\Controllers')->group(function () {
    Route::name('webhooks.mollie.default')->post(
        Cashier::webhookUrl(),
        [WebhookController::class, 'handleWebhook']
    );

    Route::name('webhooks.mollie.aftercare')->post(
        Cashier::aftercareWebhookUrl(),
        [AftercareWebhookController::class, 'handleWebhook']
    );

    Route::name('webhooks.mollie.first_payment')->post(
        Cashier::firstPaymentWebhookUrl(),
        [FirstPaymentWebhookController::class, 'handleWebhook']
    );
});
