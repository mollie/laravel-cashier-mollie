<?php

namespace Laravel\Cashier\Order;

use Money\Currency;
use Money\Money;

trait ConvertsToMoney
{
    /**
     * @param  int  $value
     * @return \Money\Money
     */
    protected function toMoney($value = 0)
    {
        return new Money(round($value), new Currency($this->getCurrency()));
    }
}
