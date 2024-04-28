<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Mollie\Api\Resources\Customer;

interface CreateMollieCustomer
{
    public function execute(array $payload, ?ProvidesOauthInformation $model = null): Customer;
}
