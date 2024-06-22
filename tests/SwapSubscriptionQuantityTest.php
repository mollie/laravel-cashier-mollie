<?php

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\SubscriptionQuantityUpdated;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\SubscriptionFactory;

class SwapSubscriptionQuantityTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withTestNow('2024-6-22');
        $this->withConfiguredPlans();

        Event::fake();
    }

    /** @test */
    public function canChangeQuantityNextCycle()
    {
        $user = $this->getUserWithZeroBalance();
        $subscription = $this->getSubscriptionForUser($user);
        $original_order_item = $subscription->scheduleNewOrderItemAt(now()->subWeeks(2));

        $this->assertTrue($subscription->scheduledOrderItem->is($original_order_item));

        // Swap to new plan
        $subscription = $subscription->updateQuantityNextCycle(3)->fresh();

        $this->assertEquals(1, $subscription->quantity);
        $this->assertEquals(3, $subscription->next_quantity);

        // Check that the billing cycle remains intact
        $cycle_should_have_started_at = now()->subWeeks(2);
        $cycle_should_end_at = $cycle_should_have_started_at->copy()->addMonth();
        $this->assertCarbon($cycle_should_have_started_at, $subscription->cycle_started_at);
        $this->assertCarbon($cycle_should_end_at, $subscription->cycle_ends_at);

        // Assert that the original scheduled OrderItem has been removed
        // And assert that another OrderItem was scheduled for the new subscription quantity
        $this->assertFalse(Cashier::$orderItemModel::whereId($original_order_item->id)->exists());
        $new_order_item = $subscription->scheduledOrderItem;
        $this->assertFalse($new_order_item->is($original_order_item));
        $this->assertCarbon($cycle_should_end_at, $new_order_item->process_at, 1); // based on previous plan's cycle
        $this->assertEquals(3, $new_order_item->quantity);

        $this->assertFalse($user->fresh()->hasCredit());

        Event::assertNotDispatched(SubscriptionQuantityUpdated::class);

        $this->assertEquals(1, $subscription->quantity);
        $this->assertEquals(3, $subscription->next_quantity);

        Subscription::processOrderItem($new_order_item);

        $subscription = $subscription->fresh();

        $this->assertNull($subscription->next_quantity);
        $this->assertEquals(3, $subscription->quantity);

        Event::assertDispatched(SubscriptionQuantityUpdated::class, function (SubscriptionQuantityUpdated $event) use ($subscription) {
            return $subscription->is($event->subscription);
        });
    }

    /** @test */
    public function changeQuantityNextCycleIllegalArguments()
    {
        $user = $this->getUserWithZeroBalance();
        $subscription = $this->getSubscriptionForUser($user);
        $original_order_item = $subscription->scheduleNewOrderItemAt(now()->subWeeks(2));

        $this->assertTrue($subscription->scheduledOrderItem->is($original_order_item));

        // Swap to new plan
        $this->assertThrows(fn() => $subscription->updateQuantityNextCycle(0)->fresh(), LogicException::class);

        $this->assertEquals(1, $subscription->quantity);
        $this->assertNull($subscription->next_quantity);

        // Check that the billing cycle remains intact
        $cycle_should_have_started_at = now()->subWeeks(2);
        $cycle_should_end_at = $cycle_should_have_started_at->copy()->addMonth();
        $this->assertCarbon($cycle_should_have_started_at, $subscription->cycle_started_at);
        $this->assertCarbon($cycle_should_end_at, $subscription->cycle_ends_at);

        // And assert that another OrderItem was scheduled for the new subscription quantity
        $this->assertTrue(Cashier::$orderItemModel::whereId($original_order_item->id)->exists());

        Event::assertNotDispatched(SubscriptionQuantityUpdated::class);
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
     * @param $user
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
            'quantity' => 1,
        ]));
    }
}

