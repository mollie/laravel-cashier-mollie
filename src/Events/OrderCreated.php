<?php

namespace Laravel\Cashier\Events;

use Laravel\Cashier\Order\Order;

class OrderCreated extends BaseEvent
{
    /**
     * The created order.
     *
     * @var Order
     */
    public $order;

    /**
     * Creates a new OrderCreated event.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }
}
