<?php

namespace Laravel\Cashier\Tests\SubscriptionBuilder;

use Carbon\Carbon;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Exceptions\CouponException;
use Laravel\Cashier\SubscriptionBuilder\MandatedSubscriptionBuilder;
use Laravel\Cashier\Tests\BaseTestCase;

class MandatedSubscriptionBuilderTest extends BaseTestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withPackageMigrations();
        $this->withConfiguredPlans();
        $this->user = $this->getCustomerUser(true, [
            'tax_percentage' => 20,
            'mollie_customer_id' => 'cst_unique_customer_id',
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
        ]);
    }

    /** @test */
    public function testWithCouponNoTrial()
    {
        $this->withMockedCouponRepository();
        $this->withMockedGetMollieMandateAccepted(2);
        $this->withMockedGetMollieCustomer(2);
        $now = Carbon::parse('2019-01-01');
        $this->withTestNow($now);

        $this->assertEquals(0, Cashier::$redeemedCouponModel::count());
        $this->assertEquals(0, Cashier::$appliedCouponModel::count());

        $builder = $this->getBuilder();

        $subscription = $builder->withCoupon('test-coupon')->create();

        $this->assertEquals(1, $subscription->redeemedCoupons()->count());

        // Coupons will be applied when (pre)processing the Subscription OrderItems
        $this->assertEquals(0, $subscription->appliedCoupons()->count());

        $orderItem = $subscription->orderItems()->first();
        $this->assertCarbon($now, $orderItem->process_at);
        $this->assertEquals('EUR', $orderItem->currency);
        $this->assertEquals(1000, $orderItem->unit_price);
        $this->assertEquals(1, $orderItem->quantity);
    }

    public function testWithCouponAndTrial()
    {
        $this->withMockedCouponRepository();
        $this->withMockedGetMollieMandateAccepted(2);
        $this->withMockedGetMollieCustomer(2);
        $now = Carbon::parse('2019-01-01');
        $this->withTestNow($now);

        $this->assertEquals(0, Cashier::$redeemedCouponModel::count());
        $this->assertEquals(0, Cashier::$appliedCouponModel::count());

        $builder = $this->getBuilder();

        $subscription = $builder
            ->withCoupon('test-coupon')
            ->trialDays(5)
            ->create();

        $this->assertEquals(1, $subscription->redeemedCoupons()->count());

        // Coupons will be applied when (pre)processing the Subscription OrderItems
        $this->assertEquals(0, $subscription->appliedCoupons()->count());

        $orderItem = $subscription->orderItems()->first();
        $this->assertCarbon($now->copy()->addDays(5), $orderItem->process_at);
        $this->assertEquals('EUR', $orderItem->currency);
        $this->assertEquals(1000, $orderItem->unit_price);
        $this->assertEquals(1, $orderItem->quantity);
    }

    /** @test */
    public function testWithCouponValidatesCoupon()
    {
        $this->expectException(CouponException::class);
        $this->withMockedCouponRepository(null, new InvalidatingCouponHandler);
        $this->withMockedGetMollieMandateAccepted(2);
        $this->withMockedGetMollieCustomer(2);
        $this->getBuilder()->withCoupon('test-coupon')->create();
    }

    /** @test */
    public function testSkipTrialWorks()
    {
        $builder = $this->getBuilder()->trialDays(5);
        $this->assertTrue($builder->makeSubscription()->onTrial());

        $builder->skipTrial();
        $this->assertFalse($builder->makeSubscription()->onTrial());
    }

    /**
     * @return \Laravel\Cashier\SubscriptionBuilder\MandatedSubscriptionBuilder
     */
    protected function getBuilder()
    {
        return new MandatedSubscriptionBuilder(
            $this->user,
            'default',
            'monthly-10-1'
        );
    }
}
