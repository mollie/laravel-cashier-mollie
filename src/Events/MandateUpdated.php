<?php

namespace Laravel\Cashier\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Mollie\Api\Resources\Payment;

class MandateUpdated
{
    use SerializesModels;

    /** @var Model */
    public $owner;

    /** @var Payment */
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
