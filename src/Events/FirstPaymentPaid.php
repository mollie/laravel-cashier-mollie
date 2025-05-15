<?php

namespace Laravel\Cashier\Events;

use Laravel\Cashier\Order\Order;

class FirstPaymentPaid extends BaseEvent
{
    /**
     * @var \Mollie\Api\Resources\Payment
     */
    public $payment;

    /**
     * The order created for this first payment.
     *
     * @var Order
     */
    public $order;

    public function __construct($payment, $order)
    {
        $this->payment = $payment;
        $this->order = $order;
    }
}
