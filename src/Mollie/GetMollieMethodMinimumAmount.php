<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Money\Money;

class GetMollieMethodMinimumAmount extends BaseMollieInteraction implements Contracts\GetMollieMethodMinimumAmount
{
    public function execute(string $method, string $currency, ?ProvidesOauthInformation $model = null): Money
    {
        $this->setAccessToken($model);

        $payload = [
            'currency' => $currency,
        ];

        if ($profile = $model?->getMollieProfile()) {
            $payload['profileId'] = $profile;
        }

        if ($model?->isMollieTestmode()) {
            $payload['testmode'] = true;
        }
        
        $minimumAmount = $this->mollie
            ->methods
            ->get($method, $payload)
            ->minimumAmount;

        return mollie_object_to_money($minimumAmount);
    }
}
