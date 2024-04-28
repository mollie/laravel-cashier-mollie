<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Mollie\Api\Resources\Customer;

interface GetMollieCustomer
{
    public function execute(string $id, ?ProvidesOauthInformation $model = null): Customer;
}
