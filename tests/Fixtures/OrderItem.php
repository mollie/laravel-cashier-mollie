<?php

namespace Laravel\Cashier\Tests\Fixtures;

use Laravel\Cashier\Order\OrderItem as CashierOrderItem;

class OrderItem extends CashierOrderItem
{
    protected $table = 'order_items';
}
