<?php

namespace Laravel\Cashier\Order\Contracts;

use Money\Money;

interface InvoicableItem
{
    /**
     * @return Money
     */
    public function getUnitPrice();

    /**
     * @return Money
     */
    public function getTotal();

    /**
     * @return Money
     */
    public function getSubtotal();

    /**
     * @return float
     *
     * @example 21.5
     */
    public function getTaxPercentage();

    /**
     * @return Money
     */
    public function getTax();
}
