<?php

namespace Laravel\Cashier\Tests\Fixtures;

use Laravel\Cashier\Refunds\RefundItem as CashierRefundItem;

class RefundItem extends CashierRefundItem
{
    protected $table = 'refund_items';
}
