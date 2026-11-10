<?php

namespace Laravel\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Order\Order;

class OrderPaymentFailedDueToInvalidMandate
{
    use SerializesModels;

    /**
     * The failed order.
     *
     * @var Order
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
