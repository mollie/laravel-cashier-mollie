<?php

namespace Laravel\Cashier\Order\Contracts;

use Mollie\Api\Resources\Mandate;
use Money\Money;

interface MaximumPayment
{
    /**
     * @return Money
     */
    public static function forMollieMandate(Mandate $mandate, $currency);
}
