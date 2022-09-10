<?php

namespace Laravel\Cashier\Tests\Coupon;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\Coupon\Contracts\CouponRepository;
use Laravel\Cashier\Coupon\Coupon;
use Laravel\Cashier\Coupon\CouponOrderItemPreprocessor;
use Laravel\Cashier\Coupon\PercentageDiscountHandler;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\Tests\BaseTestCase;

class PercentageCouponTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withPackageMigrations();
    }

    /** @test */
    public function couponCalculatesTheRightPrice()
    {
        $couponHandler = new PercentageDiscountHandler;

        $context = [
            'description' => 'Percentage coupon',
            'percentage' => 20,
        ];

        $coupon = new Coupon(
            'percentage-coupon',
            $couponHandler,
            $context
        );

        $this->withMockedCouponRepository($coupon, $couponHandler, $context);

        /** @var Subscription $subscription */
        $subscription = factory(Cashier::$subscriptionModel)->create();
        $item = factory(Cashier::$orderItemModel)->make();
        $subscription->orderItems()->save($item);

        /** @var \Laravel\Cashier\Coupon\Coupon $coupon */
        $coupon = app()->make(CouponRepository::class)->findOrFail('percentage-coupon');
        $redeemedCoupon = $coupon->redeemFor($subscription);
        $preprocessor = new CouponOrderItemPreprocessor();

        $result = $preprocessor->handle($item->toCollection());

        // discount = order item unit price / 100 * (100 + tax percentage) / 100 * percentage => 12150 / 100 * (100 + 21.5) / 100 * 20 = 2952
        $this->assertEquals(-2952, $result[1]->unit_price);
    }

    /** @test */
    public function couponCalculatesTheRightPriceBasedOnSubTotal()
    {
        $couponHandler = new PercentageDiscountHandler;

        $context = [
            'description' => 'Percentage coupon',
            'percentage' => 20,
            'discount_on_subtotal' => true,
        ];

        $coupon = new Coupon(
            'percentage-coupon',
            $couponHandler,
            $context
        );

        $this->withMockedCouponRepository($coupon, $couponHandler, $context);

        /** @var Subscription $subscription */
        $subscription = factory(Cashier::$subscriptionModel)->create();
        $item = factory(Cashier::$orderItemModel)->make();
        $subscription->orderItems()->save($item);

        /** @var \Laravel\Cashier\Coupon\Coupon $coupon */
        $coupon = app()->make(CouponRepository::class)->findOrFail('percentage-coupon');
        $redeemedCoupon = $coupon->redeemFor($subscription);
        $preprocessor = new CouponOrderItemPreprocessor();

        $result = $preprocessor->handle($item->toCollection());

        // discount = order item unit price / 100 * percentage => 12150 / 100 * 20 = 2430
        $this->assertEquals(-2430, $result[1]->unit_price);
    }
}
