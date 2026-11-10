<?php

namespace Laravel\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Order\Order;
use Laravel\Cashier\Payment;

class OrderPaymentPaid
{
    use SerializesModels;

    /**
     * The paid order.
     *
     * @var Order
     */
    public $order;

    /**
     * The paid payment.
     *
     * @var Payment
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
