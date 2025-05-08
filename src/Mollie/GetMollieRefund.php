<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;
use Laravel\Cashier\Mollie\Contracts\GetMollieRefund as Contract;
use Mollie\Api\MollieApiClient as Mollie;
use Mollie\Api\Resources\Refund;

class GetMollieRefund implements Contract
{
    protected Mollie $mollie;

    protected GetMolliePayment $getMolliePayment;

    public function __construct(Mollie $mollie, GetMolliePayment $getMolliePayment)
    {
        $this->mollie = $mollie;
        $this->getMolliePayment = $getMolliePayment;
    }

    public function execute(string $paymentId, string $refundId): Refund
    {
        $payment = $this->getMolliePayment->execute($paymentId);

        return $payment->getRefund($refundId);
    }
}
