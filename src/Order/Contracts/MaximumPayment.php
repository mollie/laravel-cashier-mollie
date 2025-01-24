<?php

namespace Laravel\Cashier\Order\Contracts;

use Mollie\Api\Resources\Mandate;

interface MaximumPayment
{
    /**
     * @return \Money\Money
     */
    public static function forMollieMandate(Mandate $mandate, $currency);
}
