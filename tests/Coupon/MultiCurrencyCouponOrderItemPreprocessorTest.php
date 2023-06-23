<?php

namespace Laravel\Cashier\Tests\Coupon;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\Coupon\Contracts\CouponRepository;
use Laravel\Cashier\Coupon\CouponOrderItemPreprocessor;
use Laravel\Cashier\Exceptions\CurrencyMismatchException;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;
use Laravel\Cashier\Tests\Database\Factories\SubscriptionFactory;

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
        $subscription = SubscriptionFactory::new()->create();
        $item = OrderItemFactory::new()->make();
        $subscription->orderItems()->save($item);

        /** @var Subscription $subscriptionUsd */
        $subscriptionUsd = SubscriptionFactory::new()->create();
        $itemUsd = OrderItemFactory::new()->USD()->make();
        $subscriptionUsd->orderItems()->save($itemUsd);

        /** @var \Laravel\Cashier\Coupon\Coupon $coupon */
        $usdCoupon = app()->make(CouponRepository::class)->findOrFail('usddiscount');

        $redeemedUsdCoupon = $usdCoupon->redeemFor($subscription);
        $preprocessor = new CouponOrderItemPreprocessor();

        $this->assertEquals(0, Cashier::$appliedCouponModel::count());
        $this->assertEquals(1, $redeemedUsdCoupon->times_left);

        $this->expectException(CurrencyMismatchException::class);
        $preprocessor->handle($item->toCollection());

        $redeemedUsdCoupon = $usdCoupon->redeemFor($subscriptionUsd);
        $preprocessor = new CouponOrderItemPreprocessor();

        $this->assertEquals(0, Cashier::$appliedCouponModel::count());
        $this->assertEquals(1, $redeemedUsdCoupon->times_left);

        $result = $preprocessor->handle($itemUsd->toCollection());

        $this->assertEquals(1, Cashier::$appliedCouponModel::count());
        $this->assertInstanceOf(OrderItemCollection::class, $result);
        $this->assertNotEquals($item->toCollection(), $result);
        $this->assertEquals(0, $redeemedUsdCoupon->refresh()->times_left);
    }
}
