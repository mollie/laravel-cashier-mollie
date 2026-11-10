<?php

namespace Laravel\Cashier\Plan\Contracts;

use Carbon\Carbon;
use Laravel\Cashier\Subscription;

interface IntervalGeneratorContract
{
    /**
     * @return Carbon
     */
    public function getEndOfNextSubscriptionCycle(?Subscription $subscription = null);
}
