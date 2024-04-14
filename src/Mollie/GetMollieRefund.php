<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;
use Laravel\Cashier\Mollie\Contracts\GetMollieRefund as Contract;
use Mollie\Api\Resources\Refund;
use Mollie\Api\MollieApiClient as Mollie;

class GetMollieRefund extends BaseMollieInteraction implements Contract
{
    /**
     * @var \Mollie\Api\MollieApiClient
     */
    protected Mollie $mollie;

    /**
     * @var \Laravel\Cashier\Mollie\Contracts\GetMolliePayment
     */
    protected GetMolliePayment $getMolliePayment;

    public function __construct(Mollie $mollie, GetMolliePayment $getMolliePayment)
    {
        $this->mollie = $mollie;
        $this->getMolliePayment = $getMolliePayment;
    }

    public function execute(string $paymentId, string $refundId, ?ProvidesOauthToken $model = null): Refund
    {
        $this->setAccessToken($model);

        $payment = $this->getMolliePayment->execute($paymentId, [], $model);

        return $payment->getRefund($refundId);
    }
}
