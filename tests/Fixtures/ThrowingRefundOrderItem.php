<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Fixtures;

use Laravel\Cashier\Refunds\RefundItem;
use RuntimeException;

class ThrowingRefundOrderItem extends OrderItem
{
    public static bool $throwOnRefund = false;

    public function handlePaymentRefunded(RefundItem $refundItem)
    {
        if (static::$throwOnRefund) {
            throw new RuntimeException('Refund item hook failed.');
        }

        return parent::handlePaymentRefunded($refundItem);
    }
}
