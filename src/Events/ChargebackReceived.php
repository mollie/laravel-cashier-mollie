<?php

declare(strict_types=1);

namespace Laravel\Cashier\Events;

use Laravel\Cashier\Payment;
use Money\Money;

class ChargebackReceived extends BaseEvent
{
    public Payment $payment;

    public Money $amountChargedBack;

    public function __construct(Payment $payment, Money $amountChargedBack)
    {
        $this->payment = $payment;
        $this->amountChargedBack = $amountChargedBack;
    }
}
