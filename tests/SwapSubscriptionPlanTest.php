<?php

namespace Laravel\Cashier\Tests;

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\OrderProcessed;
use Laravel\Cashier\Events\SubscriptionPlanSwapped;
use Laravel\Cashier\Order\Order;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;
use Laravel\Cashier\Tests\Database\Factories\SubscriptionFactory;

class SwapSubscriptionPlanTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withTestNow('2019-1-1');
        $this->withConfiguredPlans();

        Event::fake();
    }

    /** @test */
    public function can_swap_to_another_plan()
    {
        $now = now();
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);
        $this->withMockedGetMollieMethodMinimumAmount();
        $this->withMockedGetMollieMethodMaximumAmount();
        $this->withMockedCreateMolliePayment();

        $user = $this->getUserWithZeroBalance();
        $subscription = $this->getSubscriptionForUser($user);
        $alreadyPaidOrderItem = OrderItemFactory::new()->create([
            'owner_id' => $user->id,
            'order_id' => 1,
            'unit_price' => 1000,
            'tax_percentage' => 10,
        ]);
        Order::createProcessedFromItem($alreadyPaidOrderItem, [
            'id' => 1,
            'owner_id' => $user->id,
        ]);
        $originally_scheduled_order_item = $subscription->scheduleNewOrderItemAt($now->copy()->subWeeks(2));

        $this->assertTrue($subscription->scheduledOrderItem->is($originally_scheduled_order_item));

        // Swap to new plan
        $subscription = $subscription->swap('weekly-20-1')->fresh();

        $this->assertEquals('weekly-20-1', $subscription->plan);

        $this->assertCarbon($now->copy(), $subscription->cycle_started_at);
        $this->assertCarbon($now->copy()->addWeek(), $subscription->cycle_ends_at);

        // Assert that the original scheduled OrderItem has been removed
        $this->assertFalse(Cashier::$orderItemModel::whereId($originally_scheduled_order_item->id)->exists());

        // Assert that another OrderItem was scheduled for the new subscription plan
        $newly_scheduled_order_item = $subscription->scheduledOrderItem;
        $this->assertFalse($newly_scheduled_order_item->is($originally_scheduled_order_item));
        $this->assertCarbon($now->copy()->addWeek(), $newly_scheduled_order_item->process_at, 1);
        $this->assertMoneyEURCents(2200, $newly_scheduled_order_item->getTotal());
        $this->assertMoneyEURCents(200, $newly_scheduled_order_item->getTax());
        $this->assertEquals('Twice as expensive monthly subscription', $newly_scheduled_order_item->description);
        $this->assertFalse($newly_scheduled_order_item->isProcessed());

        // Assert that the amount "overpaid" for the old plan results in an additional OrderItem with negative total_amount
        $credit_item = Cashier::$orderItemModel::where('unit_price', '<', 0)->first();
        $this->assertNotNull($credit_item);
        $this->assertCarbon($now->copy(), $credit_item->process_at, 1);
        $this->assertMoneyEURCents(-603, $credit_item->getTotal());
        $this->assertMoneyEURCents(-55, $credit_item->getTax());
        $this->assertEquals('Monthly payment', $credit_item->description);
        $this->assertTrue($credit_item->isProcessed());

        // Assert that one OrderItem has already been processed
        $processed_item = Cashier::$orderItemModel::whereNotIn('id', [
            $alreadyPaidOrderItem->id,
            $newly_scheduled_order_item->id,
            $originally_scheduled_order_item->id,
            $credit_item->id,
        ])->first();
        $this->assertNotNull($processed_item);
        $this->assertCarbon($now->copy(), $processed_item->process_at, 1);
        $this->assertMoneyEURCents(2200, $processed_item->getTotal());
        $this->assertMoneyEURCents(200, $processed_item->getTax());
        $this->assertEquals('Twice as expensive monthly subscription', $processed_item->description);
        $this->assertTrue($processed_item->isProcessed());

        Event::assertDispatched(SubscriptionPlanSwapped::class, function (SubscriptionPlanSwapped $event) use ($subscription) {
            return $subscription->is($event->subscription);
        });

        $newly_scheduled_order_item->process();

        $subscription = $subscription->fresh();
        $this->assertCarbon($now->copy()->addWeek(), $subscription->cycle_started_at);
        $this->assertCarbon($now->copy()->addWeeks(2), $subscription->cycle_ends_at);

        $scheduled_order_item = $subscription->scheduledOrderItem;
        $this->assertCarbon($now->copy()->addWeeks(2), $scheduled_order_item->process_at, 1);
        $this->assertMoneyEURCents(2200, $scheduled_order_item->getTotal());
        $this->assertMoneyEURCents(200, $scheduled_order_item->getTax());
        $this->assertEquals('Twice as expensive monthly subscription', $scheduled_order_item->description);
        $this->assertFalse($scheduled_order_item->isProcessed());
    }

    /**
     * @test
     */
    public function can_swap_to_another_plan_with_immediately_applied_coupon()
    {
        $now = now();
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);
        $this->withMockedGetMollieMethodMinimumAmount();
        $this->withMockedGetMollieMethodMaximumAmount();
        $this->withMockedCreateMolliePayment();
        $this->withMockedCouponRepository();

        $user = $this->getUserWithZeroBalance();
        $subscription = $user->subscriptions()->save(SubscriptionFactory::new()->make([
            'name' => 'dummy name',
            'plan' => 'monthly-10-1',
            'cycle_started_at' => now(),
            'cycle_ends_at' => now()->addMonth(),
            'tax_percentage' => 10,
        ]));
        $alreadyPaidOrderItem = OrderItemFactory::new()->create([
            'owner_id' => $user->id,
            'order_id' => 1,
            'unit_price' => 1000,
            'tax_percentage' => 10,
        ]);
        Order::createProcessedFromItem($alreadyPaidOrderItem, [
            'id' => 1,
            'owner_id' => $user->id,
        ]);

        // redeem coupon
        $user->redeemCoupon('test-coupon', $subscription->name);

        // Swap to new plan
        $subscription = $subscription->swap('monthly-20-1')->fresh();

        $this->assertEquals('monthly-20-1', $subscription->plan);

        // Assert that another OrderItem was scheduled for the new subscription plan
        $newly_scheduled_order_item = $subscription->scheduledOrderItem;
        $this->assertCarbon($now->copy()->addMonth(), $newly_scheduled_order_item->process_at, 1);
        $this->assertMoneyEURCents(2200, $newly_scheduled_order_item->getTotal());
        $this->assertMoneyEURCents(200, $newly_scheduled_order_item->getTax());
        $this->assertEquals('Monthly payment premium', $newly_scheduled_order_item->description);
        $this->assertFalse($newly_scheduled_order_item->isProcessed());

        // Assert that the amount "overpaid" for the old plan results in an additional OrderItem with negative total_amount
        $credit_item = Cashier::$orderItemModel::where('unit_price', '<', 0)->first();
        $this->assertNotNull($credit_item);
        $this->assertCarbon($now->copy(), $credit_item->process_at, 1);
        $this->assertMoneyEURCents(-1100, $credit_item->getTotal());
        $this->assertMoneyEURCents(-100, $credit_item->getTax());
        $this->assertEquals('Monthly payment', $credit_item->description);
        $this->assertTrue($credit_item->isProcessed());

        // Assert that coupon results in an additional OrderItem with negative total_amount
        $coupon_item = Cashier::$orderItemModel::where('orderable_type', Cashier::$appliedCouponModel)->first();
        $this->assertNotNull($coupon_item);
        $this->assertCarbon($now->copy(), $coupon_item->process_at, 1);
        $this->assertMoneyEURCents(-500, $coupon_item->getTotal());
        $this->assertMoneyEURCents(0, $coupon_item->getTax());
        $this->assertEquals('Test coupon', $coupon_item->description);
        $this->assertTrue($coupon_item->isProcessed());

        Event::assertDispatched(SubscriptionPlanSwapped::class, function (SubscriptionPlanSwapped $event) use ($subscription) {
            return $subscription->is($event->subscription);
        });

        Event::assertDispatched(function (OrderProcessed $event) {
            return $event->order->items()->count() === 3
                //   2200 costs of new plan
                // - 1100 reimbursements from old plan
                // - 500 coupon
                // = 600
                && $event->order->total === 600
                //   200 tax from new plan
                // - 100 tax from old plan
                // = 100
                && $event->order->tax === 100;
        });
    }

    /** @test */
    public function swapping_a_cancelled_subscription_resumes_it()
    {
        $subscription = $this->getUser()->subscriptions()->save(
            SubscriptionFactory::new()->make([
                'ends_at' => now()->addWeek(),
                'plan' => 'monthly-20-1',
            ])
        );
        $subscription->cancel();

        $this->assertTrue($subscription->cancelled());

        $subscription->swap('weekly-20-1', false);

        $this->assertFalse($subscription->cancelled());
    }

    /** @test */
    public function swapping_a_cancelled_subscription_at_next_cycle_resumes_it()
    {
        $subscription = $this->getUser()->subscriptions()->save(
            SubscriptionFactory::new()->make([
                'ends_at' => now()->addWeek(),
                'plan' => 'monthly-20-1',
            ])
        );
        $subscription->cancel();

        $this->assertTrue($subscription->cancelled());

        $subscription->swapNextCycle('weekly-20-1');

        $this->assertFalse($subscription->cancelled());
    }

    /** @test */
    public function swapping_on_trial_does_not_create_an_order_even_when_invoice_now_is_true()
    {
        $subscription = $this->getUser()->subscriptions()->save(
            SubscriptionFactory::new()->make([
                'trial_ends_at' => now()->addWeek(),
                'plan' => 'monthly-20-1',
            ])
        );

        $this->assertTrue($subscription->onTrial());
        $this->assertEquals(0, Order::count());

        $subscription->swap('weekly-20-1', true);

        $this->assertEquals(0, Order::count());
    }

    /** @test */
    public function can_swap_next_cycle()
    {
        $user = $this->getUserWithZeroBalance();
        $subscription = $this->getSubscriptionForUser($user);
        $original_order_item = $subscription->scheduleNewOrderItemAt(now()->subWeeks(2));

        $this->assertTrue($subscription->scheduledOrderItem->is($original_order_item));

        // Swap to new plan
        $subscription = $subscription->swapNextCycle('weekly-20-1')->fresh();

        $this->assertEquals('monthly-10-1', $subscription->plan);
        $this->assertEquals('weekly-20-1', $subscription->next_plan);

        // Check that the billing cycle remains intact
        $cycle_should_have_started_at = now()->subWeeks(2);
        $cycle_should_end_at = $cycle_should_have_started_at->copy()->addMonth();
        $this->assertCarbon($cycle_should_have_started_at, $subscription->cycle_started_at);
        $this->assertCarbon($cycle_should_end_at, $subscription->cycle_ends_at);

        // Assert that the original scheduled OrderItem has been removed
        // And assert that another OrderItem was scheduled for the new subscription plan
        $this->assertFalse(Cashier::$orderItemModel::whereId($original_order_item->id)->exists());
        $new_order_item = $subscription->scheduledOrderItem;
        $this->assertFalse($new_order_item->is($original_order_item));
        $this->assertCarbon($cycle_should_end_at, $new_order_item->process_at, 1); // based on previous plan's cycle
        $this->assertEquals(2200, $new_order_item->total);
        $this->assertEquals(200, $new_order_item->tax);
        $this->assertEquals('Twice as expensive monthly subscription', $new_order_item->description);

        $this->assertFalse($user->fresh()->hasCredit());

        Event::assertNotDispatched(SubscriptionPlanSwapped::class);

        $this->assertEquals('monthly-10-1', $subscription->plan);
        $this->assertEquals('weekly-20-1', $subscription->next_plan);

        Subscription::processOrderItem($new_order_item);

        $subscription = $subscription->fresh();

        $this->assertNull($subscription->next_plan);
        $this->assertEquals('weekly-20-1', $subscription->plan);

        // Assert that the subscription cycle reflects the new plan
        $cycle_should_have_started_at = $cycle_should_end_at->copy();
        $cycle_should_end_at = $cycle_should_have_started_at->copy()->addWeek();
        $this->assertCarbon($cycle_should_have_started_at, $subscription->cycle_started_at);
        $this->assertCarbon($cycle_should_end_at, $subscription->cycle_ends_at);

        Event::assertDispatched(SubscriptionPlanSwapped::class, function (SubscriptionPlanSwapped $event) use ($subscription) {
            return $subscription->is($event->subscription);
        });
    }

    protected function getUserWithZeroBalance()
    {
        $user = $this->getMandatedUser(true, [
            'tax_percentage' => 10,
            'mollie_customer_id' => 'cst_unique_customer_id',
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
        ]);
        $this->assertEquals(0, $user->credits()->whereCurrency('EUR')->count());

        return $user;
    }

    /**
     * @return Subscription
     */
    protected function getSubscriptionForUser($user)
    {
        return $user->subscriptions()->save(SubscriptionFactory::new()->make([
            'name' => 'dummy name',
            'plan' => 'monthly-10-1',
            'cycle_started_at' => now()->subWeeks(2),
            'cycle_ends_at' => now()->subWeeks(2)->addMonth(),
            'tax_percentage' => 10,
        ]));
    }
}
