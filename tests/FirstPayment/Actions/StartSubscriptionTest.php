<?php

namespace Laravel\Cashier\Tests\FirstPayment\Actions;

use Carbon\Carbon;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\FirstPayment\Actions\StartSubscription;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Tests\BaseTestCase;

class StartSubscriptionTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this
            ->withConfiguredPlans()
            ->withTestNow('2019-01-01');
    }

    /** @test
     */
    public function canGetBasicPayload()
    {
        $action = new StartSubscription(
            $this->getMandatedUser(true, ['tax_percentage' => 20]),
            'default',
            'monthly-10-1'
        );

        $payload = $action->getPayload();

        $this->assertEquals([
            'handler' => StartSubscription::class,
            'description' => 'Monthly payment',
            'subtotal' => [
                'value' => '10.00',
                'currency' => 'EUR',
            ],
            'taxPercentage' => 20,
            'plan' => 'monthly-10-1',
            'name' => 'default',
            'quantity' => 1,
        ], $payload);
    }

    /** @test */
    public function canGetPayloadWithTrialDays()
    {
        $action = new StartSubscription(
            $this->getMandatedUser(true, ['tax_percentage' => 20]),
            'default',
            'monthly-10-1'
        );

        $action->trialDays(5);

        $payload = $action->getPayload();

        $this->assertEquals([
            'handler' => StartSubscription::class,
            'description' => 'Monthly payment',
            'subtotal' => [
                'value' => '0.00',
                'currency' => 'EUR',
            ],
            'taxPercentage' => 20,
            'plan' => 'monthly-10-1',
            'name' => 'default',
            'quantity' => 1,
            'trialDays' => 5,
        ], $payload);
    }

    /** @test */
    public function canGetPayloadWithTrialUntil()
    {
        $action = new StartSubscription(
            $this->getMandatedUser(true, ['tax_percentage' => 20]),
            'default',
            'monthly-10-1'
        );

        $action->trialUntil(now()->addDays(5));

        $payload = $action->getPayload();

        $this->assertEquals([
            'handler' => StartSubscription::class,
            'description' => 'Monthly payment',
            'subtotal' => [
                'value' => '0.00',
                'currency' => 'EUR',
            ],
            'taxPercentage' => 20,
            'plan' => 'monthly-10-1',
            'name' => 'default',
            'quantity' => 1,
            'trialUntil' => '2019-01-06T00:00:00+00:00',
        ], $payload);
    }

    /** @test */
    public function canGetPayloadWithSkipTrial()
    {
        $action = new StartSubscription(
            $this->getMandatedUser(true, ['tax_percentage' => 20]),
            'default',
            'monthly-10-1'
        );

        $action->trialUntil(now()->addDays(5))->skipTrial();

        $payload = $action->getPayload();

        $this->assertEquals([
            'handler' => StartSubscription::class,
            'description' => 'Monthly payment',
            'subtotal' => [
                'value' => '10.00',
                'currency' => 'EUR',
            ],
            'taxPercentage' => 20,
            'plan' => 'monthly-10-1',
            'name' => 'default',
            'quantity' => 1,
            'skipTrial' => true,
        ], $payload);
    }

    /** @test */
    public function canGetPayloadWithCoupon()
    {
        $this->withMockedCouponRepository();

        $action = new StartSubscription(
            $this->getMandatedUser(true, ['tax_percentage' => 20]),
            'default',
            'monthly-10-1'
        );

        $action->withCoupon('test-coupon');

        $payload = $action->getPayload();

        $this->assertEquals([
            'handler' => StartSubscription::class,
            'description' => 'Monthly payment',
            'subtotal' => [
                'value' => '10.00',
                'currency' => 'EUR',
            ],
            'taxPercentage' => 20,
            'plan' => 'monthly-10-1',
            'name' => 'default',
            'quantity' => 1,
            'coupon' => 'test-coupon',
        ], $payload);
    }

    /** @test */
    public function canCreateFromBasicPayload()
    {
        $this->assertFromPayloadToPayload();
    }

    /** @test */
    public function canCreateFromPayloadWithTrialUntil()
    {
        $this->assertFromPayloadToPayload([
            'trialUntil' => Carbon::parse('01-01-2019')->toIso8601String(),
            'subtotal' => [
                'value' => '0.00',
                'currency' => 'EUR',
            ],
        ]);
    }

    /** @test */
    public function canCreateFromPayloadWithTrialDays()
    {
        $this->assertFromPayloadToPayload([
            'trialDays' => 5,
            'subtotal' => [
                'value' => '0.00',
                'currency' => 'EUR',
            ],
        ]);
    }

    /** @test */
    public function canCreateFromPayloadWithSkipTrial()
    {
        $this->assertFromPayloadToPayload([
            'skipTrial' => true,
            'subtotal' => [
                'value' => '10.00',
                'currency' => 'EUR',
            ],
        ]);
    }

    /** @test */
    public function canCreateFromPayloadWithQuantity()
    {
        $this->assertFromPayloadToPayload([
            'quantity' => 5,
            'subtotal' => [
                'value' => '50.00',
                'currency' => 'EUR',
            ],
        ]);
    }

    /** @test */
    public function canCreateFromPayloadWithTrialAndQuantity()
    {
        $this->assertFromPayloadToPayload([
            'quantity' => 5,
            'trialDays' => 5,
            'subtotal' => [
                'value' => '0.00',
                'currency' => 'EUR',
            ],
        ]);
    }

    /** @test */
    public function canCreateFromPayloadWithCouponNoTrial()
    {
        $this->withMockedCouponRepository();
        $this->assertFromPayloadToPayload([
            'coupon' => 'test-coupon',
            'subtotal' => [
                'value' => '10.00',
                'currency' => 'EUR',
            ],
        ]);
    }

    /** @test */
    public function canCreateFromPayloadWithCouponAndTrial()
    {
        $this->withMockedCouponRepository();
        $this->assertFromPayloadToPayload([
            'coupon' => 'test-coupon',
            'trialDays' => 5,
            'subtotal' => [
                'value' => '0.00',
                'currency' => 'EUR',
            ],
        ]);
    }

    /** @test */
    public function canCreateFromPayloadWithoutTaxPercentage()
    {
        $this->withMockedCouponRepository();
        $this->assertFromPayloadToPayload([
            'coupon' => 'test-coupon',
            'trialDays' => 5,
            'subtotal' => [
                'value' => '0.00',
                'currency' => 'EUR',
            ],
            'taxPercentage' => 0,
        ]);
    }

    /** @test */
    public function canStartDefaultSubscription()
    {
        Carbon::setTestNow('2019-01-29');

        $user = $this->getMandatedUser(true, [
            'trial_ends_at' => now()->addWeek(), // on generic trial
        ]);
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);

        $this->assertFalse($user->subscribed('default'));

        $action = new StartSubscription(
            $user,
            'default',
            'monthly-10-1'
        );

        // Returns the OrderItem ready for processing right away.
        // Behind the scenes another OrderItem is scheduled for the next billing cycle.
        $items = $action->execute();
        $item = $items->first();
        $user = $user->fresh();
        $this->assertTrue($user->subscribed('default'));
        $this->assertFalse($user->onTrial());

        $this->assertInstanceOf(OrderItemCollection::class, $items);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Cashier::$orderItemModel, $item);
        $this->assertFalse($item->isProcessed());
        $this->assertCarbon(now(), $item->process_at);
        $this->assertEquals(1000, $item->total);

        $subscription = $user->subscription('default');
        $this->assertEquals(2, $subscription->orderItems()->count());
        $this->assertCarbon(now(), $subscription->cycle_started_at);
        $this->assertCarbon(Carbon::parse('2019-02-28'), $subscription->cycle_ends_at);

        $scheduledItem = $subscription->orderItems()->orderByDesc('process_at')->first();
        $this->assertCarbon(Carbon::parse('2019-02-28'), $scheduledItem->process_at);
        $this->assertEquals(1000, $scheduledItem->total);
    }

    /** @test */
    public function canStartSubscriptionWithTrialDays()
    {
        $user = $this->getMandatedUser(true, ['tax_percentage' => 20]);
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);

        $this->assertFalse($user->subscribed('default'));

        $action = new StartSubscription(
            $user,
            'default',
            'monthly-10-1'
        );

        $action->trialDays(5);

        // Returns the OrderItem ready for processing right away.
        // Behind the scenes another OrderItem is scheduled for the next billing cycle.
        $items = $action->execute();
        $item = $items->first();
        $user = $user->fresh();

        $this->assertTrue($user->subscribed('default'));
        $this->assertTrue($user->onTrial());
        $this->assertInstanceOf(OrderItemCollection::class, $items);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Cashier::$orderItemModel, $item);
        $this->assertFalse($item->isProcessed());
        $this->assertCarbon(now(), $item->process_at);
        $this->assertEquals(0, $item->total);
        $this->assertEquals(20, $item->tax_percentage);

        $subscription = $user->subscription('default');
        $this->assertEquals(2, $subscription->orderItems()->count());
        $this->assertCarbon(now(), $subscription->cycle_started_at);
        $this->assertCarbon(now()->addDays(5), $subscription->cycle_ends_at);

        $scheduledItem = $subscription->orderItems()->orderByDesc('process_at')->first();
        $this->assertCarbon(now()->addDays(5), $scheduledItem->process_at);

        $this->assertCarbon(
            now()->addDays(5),
            $user->subscriptions()->first()->trial_ends_at
        );
        $this->assertEquals(1000, $scheduledItem->subtotal);
        $this->assertEquals(1200, $scheduledItem->total);
        $this->assertEquals(20, $scheduledItem->tax_percentage);
    }

    /** @test */
    public function canStartSubscriptionWithTrialUntil()
    {
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);
        $user = $this->getMandatedUser();

        $this->assertFalse($user->subscribed('default'));

        $action = new StartSubscription(
            $user,
            'default',
            'monthly-10-1'
        );

        $action->trialUntil(now()->addDays(5));

        // Returns the OrderItem ready for processing right away.
        // Behind the scenes another OrderItem is scheduled for the next billing cycle.
        $items = $action->execute();
        $item = $items->first();
        $user = $user->fresh();

        $this->assertTrue($user->subscribed('default'));
        $this->assertTrue($user->onTrial());
        $this->assertInstanceOf(OrderItemCollection::class, $items);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Cashier::$orderItemModel, $item);
        $this->assertFalse($item->isProcessed());
        $this->assertCarbon(now(), $item->process_at);
        $this->assertEquals(0, $item->total);

        $subscription = $user->subscription('default');
        $this->assertEquals(2, $subscription->orderItems()->count());
        $this->assertCarbon(now(), $subscription->cycle_started_at);
        $this->assertCarbon(now()->addDays(5), $subscription->cycle_ends_at);

        $scheduledItem = $subscription->orderItems()->orderByDesc('process_at')->first();
        $this->assertCarbon(now()->addDays(5), $scheduledItem->process_at);

        $this->assertCarbon(
            now()->addDays(5),
            $user->subscriptions()->first()->trial_ends_at
        );
        $this->assertEquals(1000, $scheduledItem->total);
    }

    /** @test */
    public function canStartSubscriptionWithQuantityNoTrial()
    {
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);
        $user = $this->getMandatedUser();

        $this->assertFalse($user->subscribed('default'));

        $action = new StartSubscription(
            $user,
            'default',
            'monthly-10-1'
        );

        $action->quantity(5);

        // Returns the OrderItem ready for processing right away.
        // Behind the scenes another OrderItem is scheduled for the next billing cycle.
        $items = $action->execute();
        $item = $items->first();
        $user = $user->fresh();

        $this->assertTrue($user->subscribed('default'));
        $this->assertFalse($user->onTrial());
        $this->assertInstanceOf(OrderItemCollection::class, $items);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Cashier::$orderItemModel, $item);
        $this->assertFalse($item->isProcessed());
        $this->assertCarbon(now(), $item->process_at);
        $this->assertEquals(1000, $item->unit_price);
        $this->assertEquals(5, $item->quantity);
        $this->assertEquals(5000, $item->total);

        $subscription = $user->subscription('default');
        $this->assertEquals(5, $subscription->quantity);
        $this->assertEquals(2, $subscription->orderItems()->count());
        $this->assertCarbon(now(), $subscription->cycle_started_at);
        $this->assertCarbon(now()->addMonth(), $subscription->cycle_ends_at);

        $scheduledItem = $subscription->orderItems()->orderByDesc('process_at')->first();
        $this->assertCarbon(now()->addMonth(), $scheduledItem->process_at);
        $this->assertEquals(5, $scheduledItem->quantity);
        $this->assertEquals(1000, $scheduledItem->unit_price);
        $this->assertEquals(5, $scheduledItem->quantity);
        $this->assertEquals(5 * 1000, $scheduledItem->total);
    }

    /** @test */
    public function canStartSubscriptionWithQuantityAndTrialUntil()
    {
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);
        $user = $this->getMandatedUser();

        $this->assertFalse($user->subscribed('default'));

        $action = new StartSubscription(
            $user,
            'default',
            'monthly-10-1'
        );

        $action->quantity(5)->trialUntil(now()->addDays(5));

        // Returns the OrderItem ready for processing right away.
        // Behind the scenes another OrderItem is scheduled for the next billing cycle.
        $items = $action->execute();
        $item = $items->first();
        $user = $user->fresh();

        $this->assertTrue($user->subscribed('default'));
        $this->assertTrue($user->onTrial());
        $this->assertInstanceOf(OrderItemCollection::class, $items);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Cashier::$orderItemModel, $item);
        $this->assertFalse($item->isProcessed());
        $this->assertCarbon(now(), $item->process_at);
        $this->assertEquals(0, $item->total);
        $this->assertEquals(5, $item->quantity);

        $subscription = $user->subscription('default');
        $this->assertEquals(5, $subscription->quantity);
        $this->assertEquals(2, $subscription->orderItems()->count());
        $this->assertCarbon(now(), $subscription->cycle_started_at);
        $this->assertCarbon(now()->addDays(5), $subscription->cycle_ends_at);

        $scheduledItem = $subscription->orderItems()->orderByDesc('process_at')->first();
        $this->assertCarbon(now()->addDays(5), $scheduledItem->process_at);
        $this->assertEquals(5, $scheduledItem->quantity);

        $this->assertCarbon(
            now()->addDays(5),
            $user->subscriptions()->first()->trial_ends_at
        );
        $this->assertEquals(5 * 1000, $scheduledItem->total);
    }

    /** @test */
    public function canStartSubscriptionUsingNextPaymentAt()
    {
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);
        $user = $this->getMandatedUser();

        $this->assertFalse($user->subscribed('default'));

        $action = new StartSubscription(
            $user,
            'default',
            'monthly-10-1'
        );

        $action->nextPaymentAt(now()->addWeeks(2));

        // Returns the OrderItem ready for processing right away.
        // Behind the scenes another OrderItem is scheduled for the next billing cycle.
        $items = $action->execute();
        $item = $items->first();
        $user = $user->fresh();

        $this->assertTrue($user->subscribed('default'));
        $this->assertInstanceOf(OrderItemCollection::class, $items);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Cashier::$orderItemModel, $item);
        $this->assertFalse($item->isProcessed());
        $this->assertCarbon(now(), $item->process_at);

        $subscription = $user->subscription('default');
        $this->assertEquals(2, $subscription->orderItems()->count());
        $this->assertCarbon(now(), $subscription->cycle_started_at);
        $this->assertCarbon(now()->addWeeks(2), $subscription->cycle_ends_at);

        $scheduledItem = $subscription->orderItems()->orderByDesc('process_at')->first();
        $this->assertCarbon(now()->addWeeks(2), $scheduledItem->process_at);
    }

    /** @test */
    public function canStartSubscriptionWithCouponNoTrial()
    {
        $this->withMockedCouponRepository();
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);
        $user = $this->getMandatedUser();

        $this->assertFalse($user->subscribed('default'));

        $action = StartSubscription::createFromPayload([
            'name' => 'default',
            'plan' => 'monthly-10-1',
            'coupon' => 'test-coupon',
        ], $user);

        $this->assertEquals(0, Cashier::$redeemedCouponModel::count());
        $this->assertEquals(0, Cashier::$appliedCouponModel::count());

        // Returns the OrderItem ready for processing right away.
        // Behind the scenes another OrderItem is scheduled for the next billing cycle.
        /** @var OrderItemCollection $actionResult */
        $actionResult = $action->execute();
        $actionResult->save();

        $user = $user->fresh();
        $this->assertTrue($user->subscribed('default'));
        $this->assertFalse($user->onTrial());

        $this->assertInstanceOf(OrderItemCollection::class, $actionResult);
        $subscriptionItem = $actionResult->first();
        $this->assertFalse($subscriptionItem->isProcessed());
        $this->assertCarbon(now(), $subscriptionItem->process_at);

        $this->assertMoneyEURCents(1000, $subscriptionItem->getTotal());

        $subscription = $user->subscription('default');
        $this->assertEquals(1, $subscription->redeemedCoupons()->count());
        $this->assertEquals('test-coupon', $subscription->redeemedCoupons()->first()->name);
        $this->assertEquals(1, $subscription->appliedCoupons()->count());
        $this->assertEquals(2, $subscription->orderItems()->count());
        $this->assertCarbon(now(), $subscription->cycle_started_at);
        $this->assertCarbon(now()->addMonth(), $subscription->cycle_ends_at);

        $scheduledItem = $subscription->orderItems()->orderByDesc('process_at')->first();
        $this->assertCarbon(now()->addMonth(), $scheduledItem->process_at);
        $this->assertEquals(1000, $scheduledItem->total);

        $couponItem = $subscription->appliedCoupons()->first()->orderItems()->first();
        $this->assertTrue($couponItem->is($actionResult[1]));
        $this->assertEquals(-500, $couponItem->total);
    }

    /** @test */
    public function canStartSubscriptionWithCouponAndTrial()
    {
        $this->withMockedCouponRepository();
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);
        $user = $this->getMandatedUser(true, ['tax_percentage' => 20]);

        $this->assertFalse($user->subscribed('default'));

        $action = StartSubscription::createFromPayload([
            'name' => 'default',
            'plan' => 'monthly-10-1',
            'coupon' => 'test-coupon',
        ], $user);

        $action->withCoupon('test-coupon', true)
            ->trialDays(5);

        // Returns the OrderItem ready for processing right away.
        // Behind the scenes another OrderItem is scheduled for the next billing cycle.
        $items = $action->execute();
        $item = $items->first();
        $user = $user->fresh();

        $this->assertTrue($user->subscribed('default'));
        $this->assertTrue($user->onTrial());
        $this->assertInstanceOf(OrderItemCollection::class, $items);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Cashier::$orderItemModel, $item);
        $this->assertFalse($item->isProcessed());
        $this->assertCarbon(now(), $item->process_at);
        $this->assertEquals(0, $item->total);
        $this->assertEquals(20, $item->tax_percentage);

        $subscription = $user->subscription('default');
        $this->assertEquals(2, $subscription->orderItems()->count());
        $this->assertCarbon(now(), $subscription->cycle_started_at);
        $this->assertCarbon(now()->addDays(5), $subscription->cycle_ends_at);

        $this->assertEquals(1, $subscription->redeemedCoupons()->count());
        $this->assertEquals(0, $subscription->appliedCoupons()->count());
        $this->assertEquals('test-coupon', $subscription->redeemedCoupons()->first()->name);

        $scheduledItem = $subscription->orderItems()->orderByDesc('process_at')->first();
        $this->assertCarbon(now()->addDays(5), $scheduledItem->process_at);

        $this->assertCarbon(
            now()->addDays(5),
            $user->subscriptions()->first()->trial_ends_at
        );
        $this->assertEquals(1000, $scheduledItem->subtotal);
        $this->assertEquals(1200, $scheduledItem->total);
        $this->assertEquals(20, $scheduledItem->tax_percentage);
    }

    /**
     * Check if the action can be built using the payload, and then can return the same payload.
     *
     * @param  array  $overrides
     *
     * @throws \Exception
     */
    protected function assertFromPayloadToPayload($overrides = [])
    {
        $payload = array_filter(array_merge([
            'handler' => StartSubscription::class,
            'description' => 'Monthly payment',
            'subtotal' => [
                'value' => '10.00',
                'currency' => 'EUR',
            ],
            'taxPercentage' => 20,
            'plan' => 'monthly-10-1',
            'name' => 'default',
            'quantity' => 1,
        ], $overrides));

        $action = StartSubscription::createFromPayload(
            $payload,
            $this->getMandatedUser()
        );

        $result = $action->getPayload();

        $this->assertEquals($payload, $result);
    }
}
