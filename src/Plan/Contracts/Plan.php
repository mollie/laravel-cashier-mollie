<?php

declare(strict_types=1);

namespace Laravel\Cashier\Plan\Contracts;

use Laravel\Cashier\Order\OrderItemPreprocessorCollection;
use Money\Money;

interface Plan
{
    /**
     * @return Money
     */
    public function amount();

    /**
     * @return Plan
     */
    public function setAmount(Money $amount);

    /**
     * @return string
     */
    public function description();

    /**
     * @return IntervalGeneratorContract
     */
    public function interval();

    /**
     * @return string
     */
    public function name();

    /**
     * The amount the customer is charged for a mandate payment.
     *
     * @return Money
     */
    public function firstPaymentAmount();

    /**
     * @return Plan
     */
    public function setFirstPaymentAmount(Money $firstPaymentAmount);

    /**
     * @return array
     */
    public function firstPaymentMethod();

    /**
     * @param  array  $firstPaymentMethod
     * @return Plan
     */
    public function setFirstPaymentMethod($firstPaymentMethod);

    /**
     * The description for the mandate payment order item.
     *
     * @return string
     */
    public function firstPaymentDescription();

    /**
     * @return Plan
     */
    public function setFirstPaymentDescription(string $firstPaymentDescription);

    /**
     * @return string
     */
    public function firstPaymentRedirectUrl();

    /**
     * @return Plan
     */
    public function setFirstPaymentRedirectUrl(string $redirectUrl);

    /**
     * @return string
     */
    public function firstPaymentWebhookUrl();

    /**
     * @return Plan
     */
    public function setFirstPaymentWebhookUrl(string $webhookUrl);

    /**
     * @return OrderItemPreprocessorCollection
     */
    public function orderItemPreprocessors();

    /**
     * @return Plan
     */
    public function setOrderItemPreprocessors(OrderItemPreprocessorCollection $preprocessors);
}
