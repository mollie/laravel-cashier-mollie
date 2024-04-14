<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Mollie\Api\Resources\Customer;

interface GetMollieCustomer
{
    public function execute(string $id, ?ProvidesOauthToken $model = null): Customer;
}
