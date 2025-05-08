<?php

namespace Laravel\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Order\Order;

class OrderProcessed
{
    use SerializesModels;

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
