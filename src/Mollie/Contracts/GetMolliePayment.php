<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Mollie\Api\Resources\Payment;

interface GetMolliePayment
{
    public function execute(string $id, array $parameters = [], ?ProvidesOauthInformation $model = null): Payment;
}
