<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Mollie\Api\Resources\Payment;

interface UpdateMolliePayment
{
    public function execute(Payment $dirtyPayment, ?ProvidesOauthInformation $model = null): Payment;
}
