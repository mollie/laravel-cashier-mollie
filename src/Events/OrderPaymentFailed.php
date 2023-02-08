<?php

namespace Laravel\Cashier\Events;

use Illuminate\Queue\SerializesModels;

class OrderPaymentFailed
{
    use SerializesModels;

    /**
     * The failed order.
     *
     * @var \Laravel\Cashier\Order\Order
     */
    public $order;

    /**
     * The paid payment.
     *
     * @var \Laravel\Cashier\Payment
     */
    public $payment;

    /**
     * Creates a new OrderPaymentFailed event.
     *
     * @param $order
     */
    public function __construct($order, $payment)
    {
        $this->order = $order;
        $this->payment = $payment;
    }
}
