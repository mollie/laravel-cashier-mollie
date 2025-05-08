<?php

namespace Laravel\Cashier\Tests\Console;

use Laravel\Cashier\Console\Commands\SyncSubscriptionPlans;
use Laravel\Cashier\Order\OrderItem;
use Laravel\Cashier\Plan\Plan;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\SubscriptionFactory;
use Laravel\Cashier\Tests\Fixtures\User;
use Money\Money;

class SyncSubscriptionPlansTest extends BaseTestCase
{
    /** @test */
    public function it_updates_unprocessed_subscription_order_items_with_current_plan_values()
    {
        // Create an owner and subscription
        $owner = User::factory()->create();
        $subscription = SubscriptionFactory::new()->create([
            'owner_id' => $owner->id,
            'owner_type' => get_class($owner),
            'plan' => 'monthly-10-1',
        ]);
        $oldPlanPrice = Money::EUR(1000);
        $oldDescription = 'Old plan description';

        $orderItem = OrderItem::create([
            'process_at' => now(),
            'orderable_type' => get_class($subscription),
            'orderable_id' => $subscription->id,
            'owner_type' => get_class($subscription->owner),
            'owner_id' => $subscription->owner->id,
            'currency' => 'EUR',
            'unit_price' => $oldPlanPrice->getAmount(),
            'quantity' => 1,
            'tax_percentage' => 21.0,
            'description' => $oldDescription,
        ]);

        // Create a new plan with different values
        $newPlanPrice = Money::EUR(2000);
        $newDescription = 'New plan description';
        $plan = new Plan('monthly-10-1');
        $plan->setAmount($newPlanPrice);
        $plan->setDescription($newDescription);
        $this->mock(\Laravel\Cashier\Plan\Contracts\PlanRepository::class)
            ->shouldReceive('findOrFail')
            ->with('monthly-10-1')
            ->andReturn($plan);

        // Run the command
        $this->artisan(SyncSubscriptionPlans::class);

        // Assert the order item was updated
        $orderItem->refresh();
        $this->assertEquals($newPlanPrice->getAmount(), $orderItem->unit_price);
        $this->assertEquals($newDescription, $orderItem->description);
    }

    /** @test */
    public function it_skips_processed_subscription_order_items()
    {
        // Create an owner and subscription with a processed order item
        $owner = User::factory()->create();
        $subscription = SubscriptionFactory::new()->create([
            'owner_id' => $owner->id,
            'owner_type' => get_class($owner),
            'plan' => 'monthly-10-1',
        ]);
        $oldPlanPrice = Money::EUR(1000);
        $oldDescription = 'Old plan description';

        $orderItem = OrderItem::create([
            'process_at' => now(),
            'orderable_type' => get_class($subscription),
            'orderable_id' => $subscription->id,
            'owner_type' => get_class($subscription->owner),
            'owner_id' => $subscription->owner->id,
            'currency' => 'EUR',
            'unit_price' => $oldPlanPrice->getAmount(),
            'quantity' => 1,
            'tax_percentage' => 21.0,
            'description' => $oldDescription,
            'order_id' => 1, // This makes it processed
        ]);

        // Create a new plan with different values
        $newPlanPrice = Money::EUR(2000);
        $newDescription = 'New plan description';
        $plan = new Plan('monthly-10-1');
        $plan->setAmount($newPlanPrice);
        $plan->setDescription($newDescription);
        $this->mock(\Laravel\Cashier\Plan\Contracts\PlanRepository::class)
            ->shouldReceive('findOrFail')
            ->with('monthly-10-1')
            ->never(); // Should never be called since item is processed

        // Run the command
        $this->artisan(SyncSubscriptionPlans::class);

        // Assert the order item was not updated
        $orderItem->refresh();
        $this->assertEquals($oldPlanPrice->getAmount(), $orderItem->unit_price);
        $this->assertEquals($oldDescription, $orderItem->description);
    }

    /** @test */
    public function it_skips_non_subscription_order_items()
    {
        // Create a generic order item
        $orderItem = OrderItem::create([
            'process_at' => now(),
            'orderable_type' => 'App\\Models\\Product', // Some non-subscription model
            'orderable_id' => 1,
            'owner_type' => 'App\\Models\\User',
            'owner_id' => 1,
            'currency' => 'EUR',
            'unit_price' => 1000,
            'quantity' => 1,
            'tax_percentage' => 21.0,
            'description' => 'Generic product',
        ]);

        $this->mock(\Laravel\Cashier\Plan\Contracts\PlanRepository::class)
            ->shouldReceive('findOrFail')
            ->never(); // Plan repository should never be called

        // Run the command
        $this->artisan(SyncSubscriptionPlans::class);

        // Assert the order item was not updated
        $orderItem->refresh();
        $this->assertEquals(1000, $orderItem->unit_price);
        $this->assertEquals('Generic product', $orderItem->description);
    }
}
