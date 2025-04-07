<?php

namespace Laravel\Cashier\Events;

class OrderPaymentFailedDueToInvalidMandate extends BaseEvent
{
    /**
     * The failed order.
     *
     * @var \Laravel\Cashier\Order\Order
     */
    public $order;

    /**
     * Creates a new OrderPaymentFailed event.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }
}
