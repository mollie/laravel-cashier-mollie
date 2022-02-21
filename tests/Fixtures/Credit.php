<?php

namespace Laravel\Cashier\Tests\Fixtures;

use Laravel\Cashier\Credit\Credit as CashierCredit;

class Credit extends CashierCredit
{
    protected $table = 'credits';
}
