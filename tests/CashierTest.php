<?php

namespace Laravel\Cashier\Tests;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Coupon\AppliedCoupon as CashierAppliedCoupon;
use Laravel\Cashier\Coupon\RedeemedCoupon as CashierRedeemedCoupon;
use Laravel\Cashier\Credit\Credit as CashierCredit;
use Laravel\Cashier\Order\Order as CashierOrder;
use Laravel\Cashier\Order\OrderItem as CashierOrderItem;
use Laravel\Cashier\Payment as CashierPayment;
use Laravel\Cashier\Refunds\Refund as CashierRefund;
use Laravel\Cashier\Refunds\RefundItem as CashierRefundItem;
use Laravel\Cashier\Subscription as CashierSubscription;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;
use Laravel\Cashier\Tests\Database\Factories\SubscriptionFactory;
use Laravel\Cashier\Tests\Fixtures\AppliedCoupon as FixtureAppliedCoupon;
use Laravel\Cashier\Tests\Fixtures\Credit as FixtureCredit;
use Laravel\Cashier\Tests\Fixtures\Order as FixtureOrder;
use Laravel\Cashier\Tests\Fixtures\OrderItem as FixtureOrderItem;
use Laravel\Cashier\Tests\Fixtures\Payment as FixturePayment;
use Laravel\Cashier\Tests\Fixtures\RedeemedCoupon as FixtureRedeemedCoupon;
use Laravel\Cashier\Tests\Fixtures\Refund as FixtureRefund;
use Laravel\Cashier\Tests\Fixtures\RefundItem as FixtureRefundItem;
use Laravel\Cashier\Tests\Fixtures\Subscription as FixtureSubscription;
use Laravel\Cashier\Tests\Fixtures\User;
use Money\Currency;
use Money\Money;

class CashierTest extends BaseTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withConfiguredPlans();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        Cashier::useCurrencyLocale('de_DE');
        Cashier::useCurrency('eur');
    }

    /** @test */
    public function cashier_uses_predefined_models()
    {
        $this->assertEquals(Cashier::$subscriptionModel, CashierSubscription::class);
        $this->assertEquals(Cashier::$orderModel, CashierOrder::class);
        $this->assertEquals(Cashier::$orderItemModel, CashierOrderItem::class);
        $this->assertEquals(Cashier::$appliedCouponModel, CashierAppliedCoupon::class);
        $this->assertEquals(Cashier::$redeemedCouponModel, CashierRedeemedCoupon::class);
        $this->assertEquals(Cashier::$creditModel, CashierCredit::class);
        $this->assertEquals(Cashier::$paymentModel, CashierPayment::class);
        $this->assertEquals(Cashier::$refundModel, CashierRefund::class);
        $this->assertEquals(Cashier::$refundItemModel, CashierRefundItem::class);
    }

    /** @test */
    public function cashier_uses_configured_models()
    {
        Cashier::useSubscriptionModel(FixtureSubscription::class);
        Cashier::useOrderModel(FixtureOrder::class);
        Cashier::useOrderItemModel(FixtureOrderItem::class);
        Cashier::useAppliedCouponModel(FixtureAppliedCoupon::class);
        Cashier::useRedeemedCouponModel(FixtureRedeemedCoupon::class);
        Cashier::useCreditModel(FixtureCredit::class);
        Cashier::usePaymentModel(FixturePayment::class);
        Cashier::useRefundModel(FixtureRefund::class);
        Cashier::useRefundItemModel(FixtureRefundItem::class);

        $this->assertEquals(Cashier::$subscriptionModel, FixtureSubscription::class);
        $this->assertEquals(Cashier::$orderModel, FixtureOrder::class);
        $this->assertEquals(Cashier::$orderItemModel, FixtureOrderItem::class);
        $this->assertEquals(Cashier::$appliedCouponModel, FixtureAppliedCoupon::class);
        $this->assertEquals(Cashier::$redeemedCouponModel, FixtureRedeemedCoupon::class);
        $this->assertEquals(Cashier::$creditModel, FixtureCredit::class);
        $this->assertEquals(Cashier::$paymentModel, FixturePayment::class);
        $this->assertEquals(Cashier::$refundModel, FixtureRefund::class);
        $this->assertEquals(Cashier::$refundItemModel, FixtureRefundItem::class);
    }

    /** @test */
    public function test_running_cashier_processes_open_order_items()
    {
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);
        $this->withMockedGetMollieMethodMinimumAmount();
        $this->withMockedGetMollieMethodMaximumAmount();
        $this->withMockedCreateMolliePayment();

        $user = $this->getMandatedUser(true, [
            'id' => 1,
            'mollie_customer_id' => 'cst_unique_customer_id',
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
        ]);

        $user->orderItems()->save(OrderItemFactory::new()->unlinked()->processed()->make());
        $user->orderItems()->save(OrderItemFactory::new()->unlinked()->unprocessed()->make());

        $this->assertEquals(0, $user->orders()->count());
        $this->assertOrderItemCounts($user, 1, 1);

        Cashier::run();

        $this->assertEquals(1, $user->orders()->count());
        $this->assertOrderItemCounts($user, 2, 0);
    }

    /** @test */
    public function test_running_cashier_processes_unprocessed_order_items_and_schedules_next()
    {
        $this->withMockedGetMollieCustomer(2, [
            'cst_unique_customer_id_1',
            'cst_unique_customer_id_2',
        ]);
        $this->withMockedGetMollieMandateAccepted(2, [
            [
                'customerId' => 'cst_unique_customer_id_1',
                'mandateId' => 'mdt_unique_mandate_id_1',
            ],
            [
                'customerId' => 'cst_unique_customer_id_2',
                'mandateId' => 'mdt_unique_mandate_id_2',
            ],
        ]);
        $this->withMockedGetMollieMethodMinimumAmount(2);
        $this->withMockedGetMollieMethodMaximumAmount(2);
        $this->withMockedCreateMolliePayment(2);

        $user1 = $this->getMandatedUser(true, [
            'id' => 1,
            'mollie_customer_id' => 'cst_unique_customer_id_1',
            'mollie_mandate_id' => 'mdt_unique_mandate_id_1',
        ]);

        $user2 = $this->getMandatedUser(true, [
            'id' => 2,
            'mollie_customer_id' => 'cst_unique_customer_id_2',
            'mollie_mandate_id' => 'mdt_unique_mandate_id_2',
        ]);

        $subscription1 = $user1->subscriptions()->save(SubscriptionFactory::new()->make());
        $subscription2 = $user2->subscriptions()->save(SubscriptionFactory::new()->make());

        $subscription1->orderItems()->save(
            OrderItemFactory::new()->unprocessed()->EUR()->make([
                'owner_id' => 1,
                'owner_type' => User::class,
                'process_at' => now()->addHour(),
            ]) // should NOT process this (future)
        );

        $subscription1->orderItems()->saveMany(
            OrderItemFactory::new()->times(2)->unprocessed()->EUR()->make([
                'owner_id' => 1,
                'owner_type' => User::class,
                'process_at' => now()->subHour(),
            ])
        ); // should process these two

        $subscription1->orderItems()->save(
            OrderItemFactory::new()->processed()->make()
        ); // should NOT process this (already processed)

        $subscription2->orderItems()->save(
            OrderItemFactory::new()->unprocessed()->make([
                'owner_id' => 2,
                'owner_type' => User::class,
                'process_at' => now()->subHours(2),
            ])
        ); // should process this one

        $this->assertEquals(0, Cashier::$orderModel::count());
        $this->assertOrderItemCounts($user1, 1, 3);
        $this->assertOrderItemCounts($user2, 0, 1);

        Cashier::run();

        $this->assertEquals(1, $user1->orders()->count());
        $this->assertEquals(1, $user2->orders()->count());
        $this->assertOrderItemCounts($user1, 3, 3); // processed 3, scheduled 3
        $this->assertOrderItemCounts($user2, 1, 1); // processed 1, scheduled 1
    }

    /** @test */
    public function can_swap_subscription_plan()
    {
        $this->withTestNow('2019-01-01');
        $user = $this->getMandatedUser(true, [
            'id' => 1,
            'mollie_customer_id' => 'cst_unique_customer_id',
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
        ]);

        $this->withMockedGetMollieCustomer(7, ['cst_unique_customer_id']);
        $this->withMockedGetMollieMandateAccepted(7, [[
            'mandateId' => 'mdt_unique_mandate_id',
            'customerId' => 'cst_unique_customer_id',
        ]]);
        $this->withMockedGetMollieMethodMinimumAmount(2);
        $this->withMockedGetMollieMethodMaximumAmount(2);
        $this->withMockedCreateMolliePayment(2);

        $subscription = $user->newSubscription('default', 'monthly-20-1')->create();

        $this->assertOrderItemCounts($user, 0, 1);

        Cashier::run();

        $subscription = $subscription->fresh();
        $this->assertEquals(1, $user->orders()->count());
        $this->assertOrderItemCounts($user, 1, 1);
        $processedOrderItem = $user->orderItems()->processed()->first();
        $scheduledOrderItem = $subscription->scheduledOrderItem;

        // Downgrade after two weeks
        $this->withTestNow(now()->copy()->addWeeks(2));
        $subscription = $subscription->swap('monthly-10-1');

        $this->assertEquals('monthly-10-1', $subscription->plan);

        // Swapping results in a new Order being created
        $this->assertEquals(2, $user->orders()->count());

        // Added one processed OrderItem for crediting surplus
        // Added one processed OrderItem for starting the new subscription cycle
        // Removed one unprocessed OrderItem for previous plan
        // Added one unprocessed OrderItem for scheduling next subscription cycle
        $this->assertOrderItemCounts($user, 3, 1);

        $this->assertNull($scheduledOrderItem->fresh());
        $this->assertNotNull($processedOrderItem->fresh());

        // Fast-forward eight days
        $this->withTestNow(now()->addMonth());

        Cashier::run();

        // Assert that an Order for this month was created
        $this->assertEquals(3, $user->orders()->count());

        // Processed one unprocessed OrderItem
        // Scheduled one unprocessed OrderItem for next billing cycle
        $this->assertOrderItemCounts($user, 4, 1);
    }

    /** @test */
    public function can_swap_subscription_plan_and_reimburse_unused_time()
    {
        $this->withTestNow('2019-01-01');
        $user = $this->getMandatedUser(true, [
            'id' => 1,
            'mollie_customer_id' => 'cst_unique_customer_id',
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
        ]);

        $this->withMockedGetMollieCustomer(7, ['cst_unique_customer_id']);
        $this->withMockedGetMollieMandateAccepted(7, [[
            'mandateId' => 'mdt_unique_mandate_id',
            'customerId' => 'cst_unique_customer_id',
        ]]);
        $this->withMockedGetMollieMethodMinimumAmount(2, 100);
        $this->withMockedCreateMolliePayment(2);
        $this->withMockedGetMollieMethodMaximumAmount(2);
        $subscription = $user->newSubscription('default', 'monthly-10-1')->create();

        $this->assertOrderItemCounts($user, 0, 1);

        Cashier::run();

        $subscription = $subscription->fresh();
        $this->assertEquals(1, $user->orders()->count());

        $this->assertOrderItemCounts($user, 1, 1);
        $processedOrderItem = $user->orderItems()->processed()->first();
        $scheduledOrderItem = $subscription->scheduledOrderItem;

        $this->withTestNow(now()->copy()->addMinutes(1));
        $subscription = $subscription->swap('monthly-20-1');

        $this->assertEquals('monthly-20-1', $subscription->plan);
        // Swapping results in a new Order being created
        $this->assertEquals(2, $user->orders()->count());
        $this->assertEquals(1000, $user->orders()->latest()->first()->total);
    }

    /** @test */
    public function test_format_amount()
    {
        $this->assertEquals('1.000,00 €', Cashier::formatAmount(new Money(100000, new Currency('EUR'))));
        $this->assertEquals('-9.123,45 €', Cashier::formatAmount(new Money(-912345, new Currency('EUR'))));
    }

    protected function assertOrderItemCounts(Model $user, int $processed, int $unprocessed)
    {
        $this->assertEquals(
            $processed,
            $user->orderItems()->processed()->count(),
            'Unexpected amount of processed orderItems.'
        );
        $this->assertEquals(
            $unprocessed,
            $user->orderItems()->unprocessed()->count(),
            'Unexpected amount of unprocessed orderItems.'
        );
        $this->assertEquals(
            $processed + $unprocessed,
            $user->orderItems()->count(),
            'Unexpected total amount of orderItems.'
        );
    }

    /** @test */
    public function can_override_default_currency_symbol()
    {
        $this->assertEquals('€', Cashier::usesCurrencySymbol());
        $this->assertEquals('eur', Cashier::usesCurrency());

        Cashier::useCurrency('usd');

        $this->assertEquals('usd', Cashier::usesCurrency());
        $this->assertEquals('$', Cashier::usesCurrencySymbol());
    }

    /** @test */
    public function can_override_default_currency_locale()
    {
        $this->assertEquals('de_DE', Cashier::usesCurrencyLocale());

        Cashier::useCurrencyLocale('nl_NL');

        $this->assertEquals('nl_NL', Cashier::usesCurrencyLocale());
    }

    /** @test */
    public function can_override_first_payment_webhook_url()
    {
        $this->assertEquals('mandate-webhook', Cashier::firstPaymentWebhookUrl());

        config(['cashier.first_payment.webhook_url' => 'https://www.example.com/webhook/mollie']);

        $this->assertEquals('webhook/mollie', Cashier::firstPaymentWebhookUrl());

        config(['cashier.first_payment.webhook_url' => 'webhook/cashier']);

        $this->assertEquals('webhook/cashier', Cashier::firstPaymentWebhookUrl());
    }

    /** @test */
    public function can_override_webhook_url()
    {
        $this->assertEquals('webhook', Cashier::webhookUrl());

        config(['cashier.webhook_url' => 'https://www.example.com/webhook/mollie']);

        $this->assertEquals('webhook/mollie', Cashier::webhookUrl());

        config(['cashier.webhook_url' => 'webhook/cashier']);

        $this->assertEquals('webhook/cashier', Cashier::webhookUrl());
    }
}
