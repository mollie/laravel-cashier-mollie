<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Mollie\Contracts\CreateMolliePayment as Contract;
use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Mollie\Api\Resources\Payment;

class CreateMolliePayment extends BaseMollieInteraction implements Contract
{
    public function execute(array $payload, ?ProvidesOauthInformation $model = null): Payment
    {
        $this->setAccessToken($model);

        if ($profile = $model?->getMollieProfile()) {
            $payload['profileId'] = $profile;
        }

        if ($model?->isMollieTestmode()) {
            $payload['testmode'] = true;
        }

        return $this->mollie->payments->create($payload);
    }
}
