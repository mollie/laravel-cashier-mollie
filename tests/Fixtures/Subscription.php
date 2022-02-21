<?php

namespace Laravel\Cashier\Tests\Fixtures;

use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    protected $table = 'subscriptions';
}
