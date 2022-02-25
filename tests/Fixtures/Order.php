<?php

namespace Laravel\Cashier\Tests\Fixtures;

use Laravel\Cashier\Order\Order as CashierOrder;

class Order extends CashierOrder
{
    protected $table = 'orders';
}
