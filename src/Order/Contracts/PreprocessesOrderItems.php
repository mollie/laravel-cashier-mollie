<?php

namespace Laravel\Cashier\Order\Contracts;

use Laravel\Cashier\Order\OrderItem;
use Laravel\Cashier\Order\OrderItemCollection;

interface PreprocessesOrderItems
{
    /**
     * Called right before processing the order item into an order.
     *
     * @return OrderItemCollection
     */
    public static function preprocessOrderItem(OrderItem $item);
}
