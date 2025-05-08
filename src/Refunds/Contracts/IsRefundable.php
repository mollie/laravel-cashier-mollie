<?php

declare(strict_types=1);

namespace Laravel\Cashier\Refunds\Contracts;

use Laravel\Cashier\Refunds\RefundItem;

interface IsRefundable
{
    /**
     * @return void
     */
    public static function handlePaymentRefunded(RefundItem $refundItem);

    /**
     * @return void
     */
    public static function handlePaymentRefundFailed(RefundItem $refundItem);
}
