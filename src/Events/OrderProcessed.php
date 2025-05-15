<?php

namespace Laravel\Cashier\Events;

use Laravel\Cashier\Order\Order;

class OrderProcessed extends BaseEvent
{
    /**
     * The processed order.
     *
     * @var Order
     */
    public $order;

    /**
     * OrderProcessed constructor.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
