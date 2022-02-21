<?php

namespace Laravel\Cashier\Tests\Fixtures;

use Laravel\Cashier\Coupon\RedeemedCoupon as CashierRedeemedCoupon;

class RedeemedCoupon extends CashierRedeemedCoupon
{
    protected $table = 'redeemed_coupons';
}
