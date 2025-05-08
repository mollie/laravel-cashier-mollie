<?php

namespace Laravel\Cashier\Http;

use Illuminate\Http\RedirectResponse;
use Mollie\Api\Resources\Payment;

class RedirectToCheckoutResponse extends RedirectResponse
{
    protected Payment $payment;

    /**
     * @return \Laravel\Cashier\Http\RedirectToCheckoutResponse
     */
    public static function forPayment(Payment $payment, array $context = [])
    {
        $response = new static($payment->getCheckoutUrl());

        return $response
            ->setPayment($payment);
    }

    /**
     * @return \Mollie\Api\Resources\Payment
     */
    public function payment()
    {
        return $this->payment;
    }

    /**
     * @return \Laravel\Cashier\Http\RedirectToCheckoutResponse
     */
    protected function setPayment(Payment $payment)
    {
        $this->payment = $payment;

        return $this;
    }
}
