<?php

namespace Laravel\Cashier\SubscriptionBuilder;

use Illuminate\Http\RedirectResponse;
use Mollie\Api\Resources\Payment;

class RedirectToCheckoutResponse extends RedirectResponse
{
    /** @var array */
    protected $context = [];

    /** @var Payment */
    protected $payment;

    /**
     * @var FirstPaymentSubscriptionBuilder
     */
    protected $firstPaymentSubscriptionBuilder;

    /**
     * @return RedirectToCheckoutResponse
     */
    public static function forPayment(Payment $payment, array $context = [])
    {
        $response = new static($payment->getCheckoutUrl());

        return $response
            ->setPayment($payment)
            ->setContext($context);
    }

    /**
     * @return RedirectToCheckoutResponse
     */
    public static function forFirstPaymentSubscriptionBuilder(FirstPaymentSubscriptionBuilder $builder, array $context = [])
    {
        $payment = $builder->getMandatePaymentBuilder()->getMolliePayment();

        return (new static($payment->getCheckoutUrl()))
            ->setBuilder($builder)
            ->setPayment($payment)
            ->setContext($context);
    }

    /**
     * @return Payment
     */
    public function payment()
    {
        return $this->payment;
    }

    /**
     * @return array
     */
    public function context()
    {
        return $this->context;
    }

    /**
     * @return RedirectToCheckoutResponse
     */
    protected function setPayment(Payment $payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return $this
     */
    public function setContext(array $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return $this
     */
    protected function setBuilder(FirstPaymentSubscriptionBuilder $builder)
    {
        $this->firstPaymentSubscriptionBuilder = $builder;

        return $this;
    }
}
