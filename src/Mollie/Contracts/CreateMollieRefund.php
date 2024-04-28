<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Mollie\Api\Resources\Refund;

interface CreateMollieRefund
{
    public function execute(string $paymentId, array $payload, ?ProvidesOauthInformation $model = null): Refund;
}
