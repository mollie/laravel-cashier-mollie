<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Money\Money;

class GetMollieMethodMaximumAmount extends BaseMollieInteraction implements Contracts\GetMollieMethodMaximumAmount
{
    public function execute(string $method, string $currency, ?ProvidesOauthInformation $model = null): ?Money
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

        $maximumAmount = $this->mollie
            ->methods
            ->get($method, $payload)
            ->maximumAmount;

        return $maximumAmount ? mollie_object_to_money($maximumAmount) : null;
    }
}
