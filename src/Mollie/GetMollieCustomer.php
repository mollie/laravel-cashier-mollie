<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Laravel\Cashier\Mollie\Contracts\GetMollieCustomer as Contract;
use Mollie\Api\Resources\Customer;

class GetMollieCustomer extends BaseMollieInteraction implements Contract
{
    public function execute(string $id, ?ProvidesOauthToken $model = null): Customer
    {
        $this->setAccessToken($model);

        return $this->mollie->customers->get($id);
    }
}
