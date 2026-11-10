<?php

namespace Laravel\Cashier\Tests;

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\Tests\Database\Factories\OrderFactory;
use Laravel\Cashier\Tests\Database\Factories\SubscriptionFactory;
use Laravel\Cashier\Types\SubscriptionCancellationReason;
use PHPUnit\Framework\Attributes\Test;

class SubscriptionCancellationTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withConfiguredPlans()->withTestNow('2026-01-01');
        Event::fake();
    }

    #[Test]
    public function late_payment_does_not_revive_a_merchant_cancelled_subscription(): void
    {
        $subscription = SubscriptionFactory::new()->create();
        $item = $subscription->scheduleNewOrderItemAt(now());
        $item->update(['order_id' => OrderFactory::new()->create()->id]);
        $item->load('orderable');
        $subscription->cancelNow();
        $endsAt = $subscription->ends_at->copy();
        $this->withTestNow('2026-01-05');

        Subscription::handlePaymentPaid($item);

        $subscription->refresh();
        $this->assertTrue($subscription->cancelled());
        $this->assertEquals($endsAt, $subscription->ends_at);
        $this->assertSame(SubscriptionCancellationReason::REQUESTED, $subscription->cancellation_reason);
        $this->assertNull($subscription->scheduled_order_item_id);
        $this->assertSame(1, $subscription->orderItems()->count());
    }

    #[Test]
    public function late_failure_does_not_overwrite_a_merchant_cancellation(): void
    {
        $subscription = SubscriptionFactory::new()->create();
        $item = $subscription->scheduleNewOrderItemAt(now());
        $item->update(['order_id' => OrderFactory::new()->create()->id]);
        $subscription->cancel();
        $endsAt = $subscription->ends_at->copy();
        $this->withTestNow('2026-01-05');

        Subscription::handlePaymentFailed($item->fresh());
        Subscription::handlePaymentPaid($item->fresh());

        $subscription->refresh();
        $this->assertEquals($endsAt, $subscription->ends_at);
        $this->assertSame(SubscriptionCancellationReason::REQUESTED, $subscription->cancellation_reason);
        $this->assertNull($subscription->scheduled_order_item_id);
    }

    #[Test]
    public function recovered_payment_schedules_a_future_cycle_once(): void
    {
        $subscription = SubscriptionFactory::new()->create();
        $item = $subscription->scheduleNewOrderItemAt(now());
        $item->update(['order_id' => OrderFactory::new()->create()->id]);
        Subscription::handlePaymentFailed($item->fresh());
        $this->assertSame(SubscriptionCancellationReason::PAYMENT_FAILED, $subscription->refresh()->cancellation_reason);
        $this->withTestNow('2026-01-05');

        Subscription::handlePaymentPaid($item->fresh());
        Subscription::handlePaymentPaid($item->fresh());

        $subscription->refresh();
        $this->assertFalse($subscription->cancelled());
        $this->assertNull($subscription->cancellation_reason);
        $this->assertTrue($subscription->scheduledOrderItem->process_at->isFuture());
        $this->assertEquals($subscription->cycle_ends_at, $subscription->scheduledOrderItem->process_at);
        $this->assertSame(2, $subscription->orderItems()->count());
    }

    #[Test]
    public function explicitly_resuming_clears_the_cancellation_reason(): void
    {
        $subscription = SubscriptionFactory::new()->create();
        $subscription->cancel()->resume();

        $this->assertNull($subscription->refresh()->cancellation_reason);
        $this->assertFalse($subscription->cancelled());
    }

    #[Test]
    public function recovery_during_a_trial_preserves_the_trial_boundary(): void
    {
        $subscription = SubscriptionFactory::new()->create(['trial_ends_at' => now()->addDays(10)]);
        $item = $subscription->scheduleNewOrderItemAt(now());
        $item->update(['order_id' => OrderFactory::new()->create()->id]);
        Subscription::handlePaymentFailed($item);

        Subscription::handlePaymentPaid($item);

        $subscription->refresh();
        $this->assertEquals($subscription->trial_ends_at, $subscription->scheduledOrderItem->process_at);
        $this->assertNull($subscription->cancellation_reason);
    }

    #[Test]
    public function legacy_cancellations_can_still_recover_without_scheduling_in_the_past(): void
    {
        $subscription = SubscriptionFactory::new()->create([
            'ends_at' => now()->subDays(3),
            'cycle_ends_at' => null,
            'cancellation_reason' => null,
        ]);
        $item = $subscription->orderItems()->create([
            'owner_type' => $subscription->owner_type,
            'owner_id' => $subscription->owner_id,
            'process_at' => now()->subMonth(),
            'description' => 'Previous cycle',
            'currency' => 'EUR',
            'unit_price' => 1000,
            'quantity' => 1,
            'tax_percentage' => 0,
            'order_id' => OrderFactory::new()->create()->id,
        ]);

        Subscription::handlePaymentPaid($item);

        $subscription->refresh();
        $this->assertFalse($subscription->cancelled());
        $this->assertTrue($subscription->scheduledOrderItem->process_at->isFuture());
    }
}
