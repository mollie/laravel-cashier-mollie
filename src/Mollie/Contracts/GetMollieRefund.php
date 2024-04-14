<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Mollie\Api\Resources\Refund;

interface GetMollieRefund
{
    public function execute(string $paymentId, string $refundId, ?ProvidesOauthToken $model = null): Refund;
}
