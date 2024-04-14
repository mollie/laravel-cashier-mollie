<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Money\Money;

class GetMollieMethodMaximumAmount extends BaseMollieInteraction implements Contracts\GetMollieMethodMaximumAmount
{
    public function execute(string $method, string $currency, ?ProvidesOauthToken $model = null): ?Money
    {
        $this->setAccessToken($model);

        if ($profile = $model->getMollieProfile()) {
            $payload['profileId'] = $profile;
        }

        $maximumAmount = $this->mollie
            ->methods
            ->get($method, ['currency' => $currency, 'testmode' => ! app()->environment('production')])
            ->maximumAmount;

        return $maximumAmount ? mollie_object_to_money($maximumAmount) : null;
    }
}
