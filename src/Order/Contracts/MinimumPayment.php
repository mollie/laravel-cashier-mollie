<?php

namespace Laravel\Cashier\Order\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Mollie\Api\Resources\Mandate;

interface MinimumPayment
{
    /**
     * @param  \Mollie\Api\Resources\Mandate  $mandate
     * @param $currency
     * @param  \Laravel\Cashier\Contracts\ProvidesOauthInformation|null  $model
     * @return \Money\Money
     */
    public static function forMollieMandate(Mandate $mandate, $currency, ?ProvidesOauthInformation $model = null);
}
