<?php

namespace Laravel\Cashier\Order;

abstract class BaseOrderItemPreprocessor
{
    /**
     * @return \Laravel\Cashier\Order\OrderItemCollection
     */
    abstract public function handle(OrderItemCollection $items);
}
