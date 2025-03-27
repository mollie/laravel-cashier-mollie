<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Mollie\Contracts\CreateMollieRefund as Contract;
use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;
use Mollie\Api\MollieApiClient as Mollie;
use Mollie\Api\Resources\Refund;

class CreateMollieRefund extends BaseMollieInteraction implements Contract
{
    protected GetMolliePayment $getMolliePayment;

    public function __construct(Mollie $mollie, GetMolliePayment $getMolliePayment)
    {
        parent::__construct($mollie);

        $this->getMolliePayment = $getMolliePayment;
    }

    public function execute(string $paymentId, array $payload): Refund
    {
        $payment = $this->getMolliePayment->execute($paymentId);

        /** @var Refund $refund */
        $refund = $payment->refund($payload);

        return $refund;
    }
}
