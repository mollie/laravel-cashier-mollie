<?php

namespace Laravel\Cashier\Http;

use Illuminate\Http\RedirectResponse;
use Mollie\Api\Resources\Payment;

class RedirectToCheckoutResponse extends RedirectResponse
{
    protected Payment $payment;

    /**
     * @return RedirectToCheckoutResponse
     */
    public static function forPayment(Payment $payment, array $context = [])
    {
        $response = new static($payment->getCheckoutUrl());

        return $response
            ->setPayment($payment);
    }

    /**
     * @return Payment
     */
    public function payment()
    {
        return $this->payment;
    }

    /**
     * @return RedirectToCheckoutResponse
     */
    protected function setPayment(Payment $payment)
    {
        $this->payment = $payment;

        return $this;
    }
}
