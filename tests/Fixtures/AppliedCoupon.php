<?php

namespace Laravel\Cashier\Tests\Fixtures;

use Laravel\Cashier\Coupon\AppliedCoupon as CashierAppliedCoupon;

class AppliedCoupon extends CashierAppliedCoupon
{
    protected $table = 'applied_coupons';
}
