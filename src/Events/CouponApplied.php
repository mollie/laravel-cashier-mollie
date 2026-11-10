<?php

namespace Laravel\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Coupon\AppliedCoupon;
use Laravel\Cashier\Coupon\RedeemedCoupon;

class CouponApplied
{
    use SerializesModels;

    /**
     * @var RedeemedCoupon
     */
    public $redeemedCoupon;

    /**
     * @var AppliedCoupon
     */
    public $appliedCoupon;

    public function __construct(RedeemedCoupon $redeemedCoupon, AppliedCoupon $appliedCoupon)
    {
        $this->redeemedCoupon = $redeemedCoupon;
        $this->appliedCoupon = $appliedCoupon;
    }
}
