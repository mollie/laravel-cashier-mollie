<?php

declare(strict_types=1);

namespace Laravel\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Refunds\Refund;

class RefundInitiated
{
    use SerializesModels;

    /**
     * @var \Laravel\Cashier\Refunds\Refund
     */
    public Refund $refund;

    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }
}
