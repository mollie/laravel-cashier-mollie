<?php
declare(strict_types=1);

namespace Laravel\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Payment;

class ChargebackReceived
{
    use SerializesModels;

    /**
     * @var \Laravel\Cashier\Payment
     */
    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
