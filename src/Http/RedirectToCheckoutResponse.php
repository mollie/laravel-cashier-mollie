<?php

namespace Laravel\Cashier\Http;

use Illuminate\Http\RedirectResponse;
use Mollie\Api\Resources\Payment;

class RedirectToCheckoutResponse extends RedirectResponse
{
    protected Payment $payment;

    public static function forPayment(Payment $payment, array $context = []): static
    {
        $response = new static($payment->getCheckoutUrl());

        return $response
            ->setPayment($payment);
    }

    public function payment(): Payment
    {
        return $this->payment;
    }

    protected function setPayment(Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }
}
