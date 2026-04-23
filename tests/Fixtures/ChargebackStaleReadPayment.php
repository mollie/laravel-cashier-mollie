<?php

namespace Laravel\Cashier\Tests\Fixtures;

class ChargebackStaleReadPayment extends Payment
{
    protected static int $remainingStaleReads = 0;

    protected static int $staleAmountChargedBack = 0;

    public static function returnStaleChargebackAmountOnNextReads(int $reads, int $amountChargedBack = 0): void
    {
        static::$remainingStaleReads = $reads;
        static::$staleAmountChargedBack = $amountChargedBack;
    }

    public static function resetStaleChargebackReads(): void
    {
        static::$remainingStaleReads = 0;
        static::$staleAmountChargedBack = 0;
    }

    public static function findByPaymentId($id): ?self
    {
        /** @var self|null $payment */
        $payment = parent::findByPaymentId($id);

        if (! $payment || static::$remainingStaleReads <= 0) {
            return $payment;
        }

        static::$remainingStaleReads--;

        /** @var self $stalePayment */
        $stalePayment = clone $payment;
        $stalePayment->amount_charged_back = static::$staleAmountChargedBack;

        return $stalePayment;
    }
}
