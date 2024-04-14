<?php

namespace Laravel\Cashier\Order\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Mollie\Api\Resources\Mandate;

interface MaximumPayment
{
    /**
     * @param  \Mollie\Api\Resources\Mandate  $mandate
     * @param $currency
     * @param  \Laravel\Cashier\Contracts\ProvidesOauthToken|null  $model
     * @return \Money\Money
     */
    public static function forMollieMandate(Mandate $mandate, $currency, ?ProvidesOauthToken $model = null);
}
