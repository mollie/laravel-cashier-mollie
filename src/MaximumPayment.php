<?php

namespace Laravel\Cashier;

use Laravel\Cashier\Mollie\Contracts\GetMollieMethodMaximumAmount;
use Laravel\Cashier\Order\Contracts\MaximumPayment as MaximumPaymentContract;
use Mollie\Api\Resources\Mandate;
use Money\Money;

class MaximumPayment implements MaximumPaymentContract
{
    /**
     * @return Money
     */
    public static function forMollieMandate(Mandate $mandate, $currency)
    {
        /** @var GetMollieMethodMaximumAmount $getMaximumAmount */
        $getMaximumAmount = app()->make(GetMollieMethodMaximumAmount::class);

        return $getMaximumAmount->execute($mandate->method, $currency);
    }
}
