<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Mollie\Api\Resources\Payment;

interface GetMolliePayment
{
    public function execute(string $id, array $parameters = [], ?ProvidesOauthToken $model = null): Payment;
}
