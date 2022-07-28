<?php

namespace Laravel\Cashier\Traits;

use Money\Money;

trait HandlesMoneyRounding
{
    /**
     * Format the money as basic decimal
     *
     * @param  \Money\Money  $total
     * @param  float  $taxPercentage
     * @return int
     */
    public function roundingMode(Money $total, float $taxPercentage)
    {
        $vat = $total->divide(
            sprintf('%.6F', 1 + $taxPercentage)
        )->multiply(
            sprintf('%.6F', $taxPercentage)
        );

        $subtotal = $total->subtract($vat);

        $recalculatedTax = $subtotal->multiply(
            sprintf('%.6F', $taxPercentage * 100)
        )->divide(100);

        $finalTotal = $subtotal->add($recalculatedTax);

        if ($finalTotal->equals($total)) {
            return Money::ROUND_HALF_UP;
        }
        if ($finalTotal->greaterThan($total)) {
            return Money::ROUND_UP;
        }

        return  Money::ROUND_DOWN;
    }
}
