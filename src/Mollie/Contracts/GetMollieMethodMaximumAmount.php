<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Money\Money;

interface GetMollieMethodMaximumAmount
{
    public function execute(string $method, string $currency, ?ProvidesOauthInformation $model = null): ?Money;
}
