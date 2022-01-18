<?php

namespace Laravel\Cashier\Tests\Coupon;

use Laravel\Cashier\Coupon\AppliedCoupon;
use Laravel\Cashier\Coupon\Contracts\CouponRepository;
use Laravel\Cashier\Coupon\CouponOrderItemPreprocessor;
use Laravel\Cashier\Exceptions\CurrencyMismatchException;
use Laravel\Cashier\Order\OrderItem;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\Tests\BaseTestCase;

class MultiCurrencyCouponOrderItemPreprocessorTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withPackageMigrations();
    }

    /** @test */
    public function appliesCoupon()
    {
        $this->withMockedUsdCouponRepository();

        /** @var Subscription $subscription */
        $subscription = factory(Subscription::class)->create();
        $item = factory(OrderItem::class)->make();
        $subscription->orderItems()->save($item);

        /** @var Subscription $subscriptionUsd */
        $subscriptionUsd = factory(Subscription::class)->create();
        $itemUsd = factory(OrderItem::class)->make(['currency' => 'USD']);
        $subscriptionUsd->orderItems()->save($itemUsd);

        /** @var \Laravel\Cashier\Coupon\Coupon $coupon */
        $usdCoupon = app()->make(CouponRepository::class)->findOrFail('usddiscount');

        $redeemedUsdCoupon = $usdCoupon->redeemFor($subscription);
        $preprocessor = new CouponOrderItemPreprocessor();

        $this->assertEquals(0, AppliedCoupon::count());
        $this->assertEquals(1, $redeemedUsdCoupon->times_left);

        $this->expectException(CurrencyMismatchException::class);
        $preprocessor->handle($item->toCollection());


        $redeemedUsdCoupon = $usdCoupon->redeemFor($subscriptionUsd);
        $preprocessor = new CouponOrderItemPreprocessor();

        $this->assertEquals(0, AppliedCoupon::count());
        $this->assertEquals(1, $redeemedUsdCoupon->times_left);


        $result = $preprocessor->handle($itemUsd->toCollection());

        $this->assertEquals(1, AppliedCoupon::count());
        $this->assertInstanceOf(OrderItemCollection::class, $result);
        $this->assertNotEquals($item->toCollection(), $result);
        $this->assertEquals(0, $redeemedUsdCoupon->refresh()->times_left);
    }
}
