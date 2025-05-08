<?php

namespace Laravel\Cashier\Plan\Contracts;

use Laravel\Cashier\Subscription;

interface IntervalGeneratorContract
{
    /**
     * @return \Carbon\Carbon
     */
    public function getEndOfNextSubscriptionCycle(?Subscription $subscription = null);
}
