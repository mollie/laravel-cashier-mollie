<?php

namespace Laravel\Cashier\Events;

class OrderInvoiceAvailable extends BaseEvent
{
    /**
     * The created order.
     *
     * @var \Laravel\Cashier\Order\Order
     */
    public $order;

    /**
     * Creates a new OrderInvoiceAvailable event.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }
}
