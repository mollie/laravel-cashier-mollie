<?php

namespace Laravel\Cashier\Events;

class OrderPaymentFailed extends BaseEvent
{
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
     */
    public function __construct($order, $payment)
    {
        $this->order = $order;
        $this->payment = $payment;
    }
}
