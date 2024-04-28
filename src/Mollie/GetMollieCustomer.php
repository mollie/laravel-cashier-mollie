<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Laravel\Cashier\Mollie\Contracts\GetMollieCustomer as Contract;
use Mollie\Api\Resources\Customer;

class GetMollieCustomer extends BaseMollieInteraction implements Contract
{
    public function execute(string $id, ?ProvidesOauthInformation $model = null): Customer
    {
        $this->setAccessToken($model);

        $payload = [];

        if ($model?->isMollieTestmode()) {
            $payload['testmode'] = true;
        }

        return $this->mollie->customers->get($id, $payload);
    }
}
