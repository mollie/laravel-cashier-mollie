<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Mollie\Api\Resources\Refund;

interface GetMollieRefund
{
    public function execute(string $paymentId, string $refundId, ?ProvidesOauthInformation $model = null): Refund;
}
