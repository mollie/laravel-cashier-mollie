<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Laravel\Cashier\Mollie\Contracts\GetMollieMandate as Contract;
use Mollie\Api\Resources\Mandate;

class GetMollieMandate extends BaseMollieInteraction implements Contract
{
    public function execute(string $customerId, string $mandateId, ?ProvidesOauthToken $model = null): Mandate
    {
        $this->setAccessToken($model);

        return $this->mollie->mandates->getForId($customerId, $mandateId, [
            'testmode' => ! app()->environment('production'),
        ]);
    }
}
