<?php

namespace Laravel\Cashier\Order;

abstract class BaseOrderItemPreprocessor
{
    /**
     * @return OrderItemCollection
     */
    abstract public function handle(OrderItemCollection $items);
}
