<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Mollie\Api\Resources\Customer;

interface CreateMollieCustomer
{
    public function execute(array $payload, ?ProvidesOauthToken $model = null): Customer;
}
