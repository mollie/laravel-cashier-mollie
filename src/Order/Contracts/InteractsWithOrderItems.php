<?php

namespace Laravel\Cashier\Order\Contracts;

use Laravel\Cashier\Order\OrderItem;
use Laravel\Cashier\Order\OrderItemCollection;

interface InteractsWithOrderItems
{
    /**
     * Called right before processing the order item into an order.
     *
     * @return OrderItemCollection
     */
    public static function preprocessOrderItem(OrderItem $item);

    /**
     * Called after processing the order item into an order.
     *
     * @return OrderItem The order item that's being processed
     */
    public static function processOrderItem(OrderItem $item);

    /**
     * Handle a failed payment.
     *
     * @return void
     */
    public static function handlePaymentFailed(OrderItem $item);

    /**
     * Handle a paid payment.
     *
     * @return void
     */
    public static function handlePaymentPaid(OrderItem $item);
}
