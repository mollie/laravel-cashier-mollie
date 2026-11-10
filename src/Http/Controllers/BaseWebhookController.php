<?php

namespace Laravel\Cashier\Http\Controllers;

use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\Payment;

abstract class BaseWebhookController
{
    protected GetMolliePayment $getMolliePayment;

    public function __construct(GetMolliePayment $getMolliePayment)
    {
        $this->getMolliePayment = $getMolliePayment;
    }

    /**
     * Fetch a payment from Mollie using its ID.
     * Returns null if Mollie does not know the payment (404) and app.debug is off.
     * Other API errors are thrown, so the webhook is not acknowledged and Mollie calls it again.
     *
     * @return Payment|null
     *
     * @throws ApiException
     */
    public function getMolliePaymentById(string $id, array $parameters = [])
    {
        try {
            return $this->getMolliePayment->execute($id, $parameters);
        } catch (ApiException $e) {
            // An unknown id gets a plain 200 (prevent leaking information). Any other API error must not be
            // acknowledged: Mollie retries the webhook only when it does not get a 2xx.
            if (! config('app.debug') && $e->getCode() === 404) {
                return null;
            }

            throw $e;
        }
    }
}
