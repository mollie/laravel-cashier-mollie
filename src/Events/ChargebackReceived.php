<?php

declare(strict_types=1);

namespace Laravel\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Payment;
use Money\Money;

class ChargebackReceived
{
    use SerializesModels;

    /**
     * @var \Laravel\Cashier\Payment
     */
    public Payment $payment;

    public Money $amountChargedBack;

    public function __construct(Payment $payment, Money $amountChargedBack)
    {
        $this->payment = $payment;
        $this->amountChargedBack = $amountChargedBack;
    }
}
