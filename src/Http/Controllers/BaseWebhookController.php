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
     * Returns null if the payment cannot be retrieved.
     *
     * @param string $id
     * @param array $parameters
     * @return \Mollie\Api\Resources\Payment|null
     *
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function getMolliePaymentById(string $id, array $parameters = []): ?Payment
    {
        try {
            return $this->getMolliePayment->execute($id, $parameters);
        } catch (ApiException $e) {
            if (! config('app.debug')) {
                // Prevent leaking information
                return null;
            }

            throw $e;
        }
    }
}
