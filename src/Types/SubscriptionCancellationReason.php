<?php

namespace Laravel\Cashier\Types;

class SubscriptionCancellationReason
{
    /**
     * The subscription is canceled because the payment has failed.
     */
    public const string PAYMENT_FAILED = 'payment_failed';

    /**
     * The subscription is canceled by the merchant.
     */
    public const string REQUESTED = 'merchant_requested';

    /**
     * The reason for cancelling the subscription is unknown - legacy behavior.
     * @deprecated since v3.
     */
    public const string UNKNOWN = 'unknown';
}
