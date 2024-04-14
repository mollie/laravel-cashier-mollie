<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Mollie\Contracts\CreateMolliePayment as Contract;
use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Mollie\Api\Resources\Payment;

class CreateMolliePayment extends BaseMollieInteraction implements Contract
{
    public function execute(array $payload, ?ProvidesOauthToken $model = null): Payment
    {
        $this->setAccessToken($model);

        if ($profile = $model->getMollieProfile()) {
            $payload['profileId'] = $profile;
        }

        return $this->mollie->payments->create($payload + [
            'testmode' => ! app()->environment('production'),
        ]);
    }
}
