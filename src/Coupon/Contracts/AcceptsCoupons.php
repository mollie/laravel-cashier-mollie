<?php

namespace Laravel\Cashier\Coupon\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface AcceptsCoupons
{
    /**
     * @return MorphMany
     */
    public function redeemedCoupons();

    /**
     * @return MorphMany
     */
    public function appliedCoupons();

    /**
     * @return string
     */
    public function ownerType();

    /**
     * @return mixed
     */
    public function ownerId();
}
