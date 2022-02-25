<?php

namespace Laravel\Cashier\Tests\Fixtures;

use Laravel\Cashier\Refunds\Refund as CashierRefund;

class Refund extends CashierRefund
{
    protected $table = 'refunds';
}
