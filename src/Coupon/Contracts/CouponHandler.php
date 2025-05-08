<?php

namespace Laravel\Cashier\Coupon\Contracts;

use Laravel\Cashier\Coupon\Coupon;
use Laravel\Cashier\Coupon\RedeemedCoupon;
use Laravel\Cashier\Exceptions\CouponException;
use Laravel\Cashier\Order\OrderItemCollection;

interface CouponHandler
{
    /**
     * @return \Laravel\Cashier\Coupon\Contracts\CouponHandler
     */
    public function withContext(array $context);

    /**
     * @return bool
     *
     * @throws \Throwable|CouponException
     */
    public function validate(Coupon $coupon, AcceptsCoupons $model);

    /**
     * Apply the coupon to the OrderItemCollection
     *
     * @return \Laravel\Cashier\Order\OrderItemCollection
     */
    public function handle(RedeemedCoupon $redeemedCoupon, OrderItemCollection $items);

    /**
     * @return \Laravel\Cashier\Order\OrderItemCollection
     */
    public function getDiscountOrderItems(OrderItemCollection $items);
}
