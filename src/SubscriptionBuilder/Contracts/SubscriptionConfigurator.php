<?php

namespace Laravel\Cashier\SubscriptionBuilder\Contracts;

use Carbon\Carbon;

interface SubscriptionConfigurator
{
    /**
     * Specify the number of days of the trial.
     *
     * @return $this
     */
    public function trialDays(int $trialDays);

    /**
     * Specify the ending date of the trial.
     *
     * @return $this
     */
    public function trialUntil(Carbon $trialUntil);

    /**
     * Force the trial to end immediately.
     *
     * @return $this
     */
    public function skipTrial();

    /**
     * Override the default next payment date.
     *
     * @return $this
     */
    public function nextPaymentAt(Carbon $nextPaymentAt);

    /**
     * Specify the quantity of the subscription.
     *
     * @return $this
     */
    public function quantity(int $quantity);

    /**
     * Specify a discount coupon.
     *
     * @return $this
     */
    public function withCoupon(string $coupon);
}
