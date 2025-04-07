<?php

namespace Laravel\Cashier\Events;

use Illuminate\Database\Eloquent\Model;
use Mollie\Api\Resources\Payment;

class MandateUpdated extends BaseEvent
{
    /** @var \Illuminate\Database\Eloquent\Model */
    public $owner;

    /** @var \Mollie\Api\Resources\Payment */
    public $payment;

    /**
     * MandateUpdated constructor.
     */
    public function __construct(Model $owner, Payment $payment)
    {
        $this->owner = $owner;
        $this->payment = $payment;
    }
}
