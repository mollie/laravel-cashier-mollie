<?php

namespace Laravel\Cashier\Tests\Fixtures;

use Laravel\Cashier\Payment as CashierPayment;

class Payment extends CashierPayment
{
    protected $table = 'payments';
}
