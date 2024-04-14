<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Money\Money;

interface GetMollieMethodMinimumAmount
{
    public function execute(string $method, string $currency, ?ProvidesOauthToken $model = null): Money;
}
