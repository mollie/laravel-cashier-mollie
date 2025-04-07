<?php

declare(strict_types=1);

namespace Laravel\Cashier\Events;

use Laravel\Cashier\Refunds\Refund;

class RefundProcessed extends BaseEvent
{

    public Refund $refund;

    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }
}
