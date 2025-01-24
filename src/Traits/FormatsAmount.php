<?php

namespace Laravel\Cashier\Traits;

use Laravel\Cashier\Cashier;
use Money\Money;

trait FormatsAmount
{
    /**
     * Format the given amount into a string.
     *
     * @return string
     */
    protected function formatAmount(Money $amount)
    {
        return Cashier::formatAmount($amount);
    }
}
