<?php

namespace Laravel\Cashier\Order\Contracts;

use Laravel\Cashier\Order\OrderItem;

interface InteractsWithOrderItems
{
    /**
     * Called right before processing the order item into an order.
     *
     * @return \Laravel\Cashier\Order\OrderItemCollection
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
