<?php

namespace Laravel\Cashier\Events;

use Illuminate\Queue\SerializesModels;

class OrderPaymentPaid
{
    use SerializesModels;

    /**
     * The paid order.
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
     * Creates a new OrderPaymentPaid event.
     */
    public function __construct($order, $payment)
    {
        $this->order = $order;
        $this->payment = $payment;
    }
}
