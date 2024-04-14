<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Mollie\Api\Resources\Payment;

interface UpdateMolliePayment
{
    public function execute(Payment $dirtyPayment, ?ProvidesOauthToken $model = null): Payment;
}
