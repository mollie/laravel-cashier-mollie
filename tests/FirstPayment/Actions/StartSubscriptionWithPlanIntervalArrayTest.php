<?php

namespace Laravel\Cashier\Tests\FirstPayment\Actions;

use Carbon\Carbon;
use Laravel\Cashier\FirstPayment\Actions\StartSubscription;
use Laravel\Cashier\Tests\BaseTestCase;

class StartSubscriptionWithPlanIntervalArrayTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this
            ->withConfiguredPlansWithIntervalArray()
            ->withTestNow('2019-01-29');
    }

    /** @test */
    public function can_start_subscription_with_fixed_interval_test()
    {
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandate(2);
        $user = $this->getMandatedUser();

        $this->assertFalse($user->subscribed('default'));

        $action = new StartSubscription(
            $user,
            'default',
            'withfixedinterval-10-1'
        );

        $items = $action->execute();
        $item = $items->first();
        $user = $user->fresh();

        $this->assertTrue($user->subscribed('default'));
        $this->assertFalse($user->onTrial());
        $subscription = $user->subscription('default');
        $this->assertEquals(1, $subscription->quantity);
        $this->assertCarbon(now(), $subscription->cycle_started_at);
        $this->assertCarbon(Carbon::parse('2019-02-28'), $subscription->cycle_ends_at);
    }

    /** @test */
    public function can_start_subscription_without_fixed_interval()
    {
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandate(2);
        $user = $this->getMandatedUser();

        $this->assertFalse($user->subscribed('default'));

        $action = new StartSubscription(
            $user,
            'default',
            'withoutfixedinterval-10-1'
        );

        $items = $action->execute();
        $item = $items->first();
        $user = $user->fresh();

        $this->assertTrue($user->subscribed('default'));
        $this->assertFalse($user->onTrial());
        $subscription = $user->subscription('default');
        $this->assertEquals(1, $subscription->quantity);
        $this->assertCarbon(now(), $subscription->cycle_started_at);
        $this->assertCarbon(Carbon::parse('2019-03-01'), $subscription->cycle_ends_at);
    }
}
