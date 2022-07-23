<?php

namespace Laravel\Cashier\Types;

class SubscriptionCancellationReason
{
    /**
     * The reason for cancelling the subscription is unknown.
     */
    public const UNKNOWN = "unknown";

    /**
     * The subscription is canceled because the payment has failed.
     */
    public const PAYMENT_FAILED = "payment_failed";
}
