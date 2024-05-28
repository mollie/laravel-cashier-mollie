<?php

namespace Laravel\Cashier\Tests\Order;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\BalanceTurnedStale;
use Laravel\Cashier\Events\OrderCreated;
use Laravel\Cashier\Events\OrderPaymentFailedDueToInvalidMandate;
use Laravel\Cashier\Events\OrderProcessed;
use Laravel\Cashier\Exceptions\AmountExceedsMolliePaymentMethodLimit;
use Laravel\Cashier\Order\Invoice;
use Laravel\Cashier\Order\Order;
use Laravel\Cashier\Order\OrderCollection;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderFactory;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;
use Laravel\Cashier\Tests\Database\Factories\SubscriptionFactory;
use Laravel\Cashier\Tests\Fixtures\User;
use Mollie\Api\Types\PaymentStatus;
use Money\Currency;
use Money\Money;

class OrderTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withConfiguredPlans();
    }

    /** @test */
    public function canCreateFromOrderItemsAndProcess()
    {
        Carbon::setTestNow(Carbon::parse('2018-01-01'));
        Event::fake();

        $this->withMockedGetMollieMandateAccepted(2);
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMethodMinimumAmount();
        $this->withMockedGetMollieMethodMaximumAmount();
        $this->withMockedCreateMolliePayment();

        $user = $this->getMandatedUser(true, [
            'id' => 2,
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);
        $subscription = $this->createMonthlySubscription();

        $subscription->orderItems()->saveMany(
            OrderItemFactory::new()->times(2)->make([
                'process_at' => now()->subMinute(), // sub minute so we're sure it's ready to be processed
                'owner_id' => $user->id,
                'owner_type' => get_class($user),
                'currency' => 'EUR',
                'quantity' => 1,
                'unit_price' => 12345, // includes vat
                'tax_percentage' => 21.5,
            ])
        );

        $order = Cashier::$orderModel::createFromItems(Cashier::$orderItemModel::all());

        $this->assertEquals(2, $order->items()->count());

        $this->assertEquals(2, $order->owner_id);
        $this->assertEquals(User::class, $order->owner_type);
        $this->assertEquals('EUR', $order->currency);
        $this->assertEquals(24690, $order->subtotal);
        $this->assertMoneyEURCents(24690, $order->getSubtotal());
        $this->assertEquals(5308, $order->tax);
        $this->assertMoneyEURCents(5308, $order->getTax());
        $this->assertEquals(29998, $order->total);
        $this->assertMoneyEURCents(29998, $order->getTotal());
        $this->assertEquals('2018-0000-0001', $order->number);
        $this->assertNull($order->mollie_payment_id);

        Event::assertDispatched(OrderCreated::class, function ($e) use ($order) {
            return $e->order->is($order);
        });

        $order->processPayment();

        $this->assertDispatchedOrderProcessed($order);

        $this->assertEquals('tr_unique_payment_id', $order->mollie_payment_id);
        $this->assertEquals('open', $order->mollie_payment_status);
    }

    /** @test */
    public function creatingANewOrderSchedulesNextOrderItems()
    {
        $user = User::factory()->create(['id' => 2]);
        $subscription = SubscriptionFactory::new()->create([
            'owner_id' => $user->id,
            'owner_type' => get_class($user),
            'plan' => 'monthly-10-1',
            'cycle_ends_at' => now(),
        ]);

        $subscription->scheduleNewOrderItemAt($subscription->cycle_ends_at);

        $this->assertEquals(1, $subscription->orderItems()->count());

        $order = Cashier::$orderModel::createFromItems($subscription->orderItems);

        $subscription = $subscription->fresh();

        $this->assertEquals(1, Cashier::$orderModel::count());
        $this->assertEquals(1, $order->items()->count());
        $this->assertEquals(2, $subscription->orderItems()->count());
        $this->assertEquals(1, Cashier::$orderItemModel::unprocessed()->count());

        $scheduled_item = $subscription->scheduled_order_item;

        $this->assertCarbon(now()->addMonth(), $subscription->cycle_ends_at);
        $this->assertCarbon(now()->addMonth(), $scheduled_item->process_at);

        $this->assertEquals(2, $scheduled_item->owner_id);
        $this->assertSame(User::class, $scheduled_item->owner_type);
        $this->assertSame('EUR', $scheduled_item->currency);
        $this->assertSame(1, $scheduled_item->quantity);
        $this->assertSame(1000, $scheduled_item->unit_price);
        $this->assertSame(0.0, $scheduled_item->tax_percentage);
    }

    /** @test */
    public function yieldsOrderCollection()
    {
        $collection = OrderFactory::new()->times(2)->make();

        $this->assertInstanceOf(OrderCollection::class, $collection);
    }

    /** @test */
    public function handlesOwnerBalance()
    {
        // Owner with 15 euro balance
        $user = $this
            ->getMandatedUser(true, ['id' => 2])
            ->addCredit(new Money(1500, new Currency('EUR')));

        $this->assertMoneyEURCents(1500, $user->credit('EUR')->money());

        $subscription = SubscriptionFactory::new()->create([
            'owner_id' => $user->id,
            'owner_type' => get_class($user),
            'plan' => 'monthly-10-1',
            'cycle_ends_at' => now(),
        ]);

        $subscription->scheduleNewOrderItemAt($subscription->cycle_ends_at);

        $scheduled_item = $subscription->scheduled_order_item;

        $order = Cashier::$orderModel::createFromItems(new OrderItemCollection([$scheduled_item]))->fresh();

        $this->assertEquals(2, $order->owner_id);
        $this->assertSame(User::class, $order->owner_type);
        $this->assertSame('EUR', $order->currency);
        $this->assertSame(0, $order->tax);
        $this->assertSame(1000, $order->subtotal);
        $this->assertSame(1000, $order->total);
        $this->assertSame(1000, $order->total_due);
        $this->assertSame(0, $order->credit_used);
        $this->assertSame(0, $order->balance_before);

        $this->assertEquals(0, $order->balance_after);
        $this->assertMoneyEURCents(0, $order->getBalanceAfter());
        $this->assertFalse($order->creditApplied());

        $this->assertEquals(1500, $user->credit('EUR')->value);
        $this->assertMoneyEURCents(1500, $user->credit('EUR')->money());

        $order = $order->processPayment();

        $this->assertTrue($order->creditApplied());

        $this->assertEquals(1500, $order->balance_before);
        $this->assertMoneyEURCents(1500, $order->getBalanceBefore());

        $this->assertEquals(1000, $order->credit_used);
        $this->assertMoneyEURCents(1000, $order->getCreditUsed());

        $this->assertEquals(500, $order->balance_after);
        $this->assertMoneyEURCents(500, $order->getBalanceAfter());

        $this->assertEquals(500, $user->credit('EUR')->value);
        $this->assertMoneyEURCents(500, $user->credit('EUR')->money());

        $this->assertEquals(2, $order->owner_id);
        $this->assertSame(User::class, $order->owner_type);
        $this->assertSame('EUR', $order->currency);
        $this->assertSame(0, $order->tax);
        $this->assertSame(1000, $order->subtotal);
        $this->assertSame(1000, $order->total);
        $this->assertSame(0, $order->total_due);
        $this->assertSame(1000, $order->credit_used);
        $this->assertSame(1500, $order->balance_before);
    }

    /** @test */
    public function canGetInvoice()
    {
        $user = User::factory()->create(['extra_billing_information' => "Some dummy\nextra billing information"]);
        $items = OrderItemFactory::new()->times(2)->unlinked()->EUR()->create([
            'owner_id' => $user->id,
            'owner_type' => User::class,
            'unit_price' => 12150,
            'quantity' => 1,
            'tax_percentage' => 21.5,
        ]);
        $order = Cashier::$orderModel::createFromItems($items, [
            'balance_before' => 500,
            'credit_used' => 500,
        ]);
        $date = Carbon::parse('2017-06-06');

        $invoice = $order->invoice('2017-0000-0001', $date);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('2017-0000-0001', $invoice->id());
        $this->assertEquals($date, $invoice->date());
        $this->assertCount(2, $invoice->items());
        $this->assertEquals(collect(['Some dummy', 'extra billing information']), $invoice->extraInformation());

        $this->assertMoneyEURCents(500, $invoice->rawStartingBalance());
        $this->assertMoneyEURCents(500, $invoice->rawUsedBalance());
        $this->assertMoneyEURCents(0, $invoice->rawCompletedBalance());
        $this->assertMoneyEURCents(29524, $invoice->rawTotal());
        $this->assertMoneyEURCents(29024, $invoice->rawTotalDue());
    }

    /** @test */
    public function doesNotProcessPaymentIfTotalDueIsZero()
    {
        Event::fake();

        $user = $this->getMandatedUser(true);
        $order = $user->orders()->save(OrderFactory::new()->make([
            'total' => 0,
            'total_due' => 0,
            'currency' => 'EUR',
        ]));
        $this->assertFalse($order->isProcessed());
        $this->assertFalse($user->hasCredit('EUR'));

        $order->processPayment();

        $this->assertTrue($order->isProcessed());
        $this->assertNull($order->mollie_payment_id);
        $this->assertFalse($user->hasCredit('EUR'));

        $this->assertDispatchedOrderProcessed($order);
    }

    /** @test */
    public function createsAMolliePaymentIfTotalDueIsLargerThanMolliesMinimum()
    {
        Event::fake();

        $this->withMockedGetMollieMandateAccepted(2);
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMethodMinimumAmount();
        $this->withMockedGetMollieMethodMaximumAmount();
        $this->withMockedCreateMolliePayment();

        $user = $this->getMandatedUser(true, [
            'id' => 2,
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $order = $user->orders()->save(OrderFactory::new()->make([
            'total' => 1025,
            'total_due' => 1025,
            'currency' => 'EUR',
        ]));
        $this->assertFalse($order->isProcessed());
        $this->assertFalse($user->hasCredit('EUR'));

        $order->processPayment();

        $this->assertTrue($order->isProcessed());
        $this->assertNotNull($order->mollie_payment_id);
        $this->assertEquals('open', $order->mollie_payment_status);

        $this->assertDispatchedOrderProcessed($order);
    }

    /** @test */
    public function createsAMolliePaymentIfMolliesMaximumIsNull()
    {
        Event::fake();

        $this->withMockedGetMollieMandateAccepted(2);
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMethodMinimumAmount();
        $this->withMockedGetMollieMethodMaximumAmount(1, null);
        $this->withMockedCreateMolliePayment();

        $user = $this->getMandatedUser(true, [
            'id' => 2,
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $order = $user->orders()->save(OrderFactory::new()->make([
            'total' => 1025,
            'total_due' => 1025,
            'currency' => 'EUR',
        ]));
        $this->assertFalse($order->isProcessed());
        $this->assertFalse($user->hasCredit('EUR'));

        $order->processPayment();

        $this->assertTrue($order->isProcessed());
        $this->assertNotNull($order->mollie_payment_id);
        $this->assertEquals('open', $order->mollie_payment_status);

        $this->assertDispatchedOrderProcessed($order);
    }

    /** @test */
    public function notCreatesAMolliePaymentIfTotalDueIsGreathenThanMolliesMaximum()
    {
        Event::fake();

        $this->withMockedGetMollieMandateAccepted(2);
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMethodMinimumAmount();
        $this->withMockedGetMollieMethodMaximumAmount(1, 10);

        $user = $this->getMandatedUser(true, [
            'id' => 2,
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $order = $user->orders()->save(OrderFactory::new()->make([
            'total' => 1025,
            'total_due' => 1025,
            'currency' => 'EUR',
        ]));
        $this->assertFalse($order->isProcessed());
        $this->assertFalse($user->hasCredit('EUR'));

        $this->expectException(AmountExceedsMolliePaymentMethodLimit::class);
        $order->processPayment();
    }

    /** @test */
    public function handlesAnInvalidMandateWhenProcessingThePayment()
    {
        Event::fake();

        $this->withMockedGetMollieMandateRevoked();
        $this->withMockedGetMollieCustomer();

        $user = $this->getMandatedUser(true, [
            'id' => 2,
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $order = $user->orders()->save(OrderFactory::new()->make([
            'total' => 1025,
            'total_due' => 1025,
            'currency' => 'EUR',
        ]));
        $this->assertFalse($order->isProcessed());
        $this->assertFalse($user->hasCredit('EUR'));

        $order->processPayment();

        $this->assertTrue($order->isProcessed());
        $this->assertNull($order->mollie_payment_id);
        $this->assertEquals('failed', $order->mollie_payment_status);

        Event::assertDispatched(OrderPaymentFailedDueToInvalidMandate::class, function ($event) use ($order) {
            return $order->is($event->order);
        });
    }

    /** @test */
    public function canRetryAFailedOrderNow()
    {
        $this->withMockedGetMollieMandateAccepted(3);
        $this->withMockedGetMollieCustomer(3);
        $this->withMockedGetMollieMethodMinimumAmount();
        $this->withMockedGetMollieMethodMaximumAmount();
        $this->withMockedCreateMolliePayment(1, '10.25', 'tr_new_payment_id');

        $user = $this->getMandatedUser(true, [
            'id' => 2,
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $order = $user->orders()->save(OrderFactory::new()->make([
            'processed_at' => now()->subMinutes(5),
            'total' => 10.25,
            'total_due' => 10.25,
            'currency' => 'EUR',
            'mollie_payment_status' => 'failed',
            'mollie_payment_id' => 'tr_1234',
        ]));

        $this->assertTrue($order->isProcessed());
        $this->assertDatabaseCount('orders', 1);
        $this->assertEquals('failed', $order->mollie_payment_status);
        $this->assertSame($order->mollie_payment_id, 'tr_1234');
        $this->assertDatabaseCount('payments', 0);

        $order->retryNow();
        $order->refresh();
        $this->assertDatabaseCount('orders', 1);

        $this->assertTrue($order->isProcessed());
        $this->assertSame($order->mollie_payment_id, 'tr_new_payment_id');
        $this->assertSame('open', $order->mollie_payment_status);

        $this->assertDatabaseCount('payments', 1);
        $this->assertDatabaseHas('payments', ['mollie_payment_id' => 'tr_new_payment_id']);
    }

    /** @test */
    public function storesOwnerCreditIfTotalIsPositiveAndSmallerThanMolliesMinimum()
    {
        Event::fake();

        $this->withMockedGetMollieMandateAccepted(2);
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMethodMinimumAmount(1, 100);
        $this->withMockedGetMollieMethodMaximumAmount();

        $user = $this->getMandatedUser(true, [
            'id' => 2,
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);
        $subscription = $user->subscriptions()->save(SubscriptionFactory::new()->make([
            'plan' => 'monthly-10-1',
        ]));

        $order = $user->orders()->save(OrderFactory::new()->make([
            'total' => 25,
            'total_due' => 25, // minimum for processing is 100
            'currency' => 'EUR',
        ]));
        $this->assertFalse($order->isProcessed());
        $this->assertFalse($user->hasCredit('EUR'));
        $this->assertTrue($subscription->active());

        $order->processPayment();

        $this->assertTrue($order->isProcessed());
        $this->assertNull($order->mollie_payment_id);
        $this->assertTrue($user->hasCredit('EUR'));
        $this->assertEquals(-25, $user->credit('EUR')->value);
        $this->assertMoneyEURCents(-25, $user->credit('EUR')->money());

        Event::assertNotDispatched(BalanceTurnedStale::class);
        $this->assertDispatchedOrderProcessed($order);
    }

    /** @test */
    public function storesOwnerCreditIfTotalDueIsNegativeAndOwnerHasActiveSubscription()
    {
        Event::fake();

        $user = $this->getMandatedUser(true);
        $subscription = $user->subscriptions()->save(SubscriptionFactory::new()->make([
            'plan' => 'monthly-10-1',
        ]));

        $order = $user->orders()->save(OrderFactory::new()->make([
            'total' => -1025,
            'total_due' => -1025,
            'currency' => 'EUR',
        ]));
        $this->assertFalse($order->isProcessed());
        $user->addCredit(new Money(1025, new Currency('EUR')));
        $this->assertTrue($user->hasCredit('EUR'));
        $this->assertEquals(1025, $user->credit('EUR')->value);
        $this->assertMoneyEURCents(1025, $user->credit('EUR')->money());
        $this->assertTrue($subscription->active());

        $order->processPayment();

        $this->assertTrue($order->isProcessed());
        $this->assertNull($order->mollie_payment_id);
        $this->assertTrue($user->hasCredit('EUR'));
        $this->assertEquals(2050, $user->credit('EUR')->value);
        $this->assertMoneyEURCents(2050, $user->credit('EUR')->money());

        Event::assertNotDispatched(BalanceTurnedStale::class);
        $this->assertDispatchedOrderProcessed($order);
    }

    /** @test */
    public function handlesNegativeTotalDueAndOwnerHasNoActiveSubscription()
    {
        Event::fake();

        $user = $this->getMandatedUser(true);

        $order = $user->orders()->save(OrderFactory::new()->make([
            'total' => -1,
            'total_due' => -1,
            'currency' => 'EUR',
        ]));

        $this->assertFalse($order->isProcessed());
        $this->assertFalse($user->hasCredit('EUR'));

        $order->processPayment();

        $this->assertTrue($order->isProcessed());
        $this->assertNull($order->mollie_payment_id);
        $this->assertTrue($user->hasCredit('EUR'));
        $credit = $user->credit('EUR');
        $this->assertEquals(1, $credit->value);
        $this->assertMoneyEURCents(1, $credit->money());

        $this->assertDispatchedOrderProcessed($order);

        Event::assertDispatched(BalanceTurnedStale::class, function ($event) use ($credit) {
            return $credit->is($event->credit);
        });
    }

    /**
     * @test
     */
    public function canCreateOrderFromOrderItemsWhenTotalValueIsNegativeAndOwnerHasNoMandate()
    {
        Carbon::setTestNow(Carbon::parse('2018-01-01'));
        Event::fake();
        $user = User::factory()->create(); // user without subscription/mandate

        OrderItemFactory::new()->times(2)->create([
            'orderable_type' => null,
            'orderable_id' => null,
            'process_at' => now()->subMinute(), // sub minute so we're sure it's ready to be processed
            'owner_id' => $user->id,
            'owner_type' => get_class($user),
            'currency' => 'EUR',
            'quantity' => 1,
            'unit_price' => -12345, // includes vat
            'tax_percentage' => 21.5,
        ]);

        $order = Cashier::$orderModel::createFromItems(Cashier::$orderItemModel::all());

        $this->assertEquals(2, $order->items()->count());

        $this->assertEquals($user->id, $order->owner_id);
        $this->assertEquals(User::class, $order->owner_type);
        $this->assertEquals('EUR', $order->currency);
        $this->assertEquals(-24690, $order->subtotal);
        $this->assertMoneyEURCents(-24690, $order->getSubtotal());
        $this->assertEquals(-5308, $order->tax);
        $this->assertMoneyEURCents(-5308, $order->getTax());
        $this->assertEquals(-29998, $order->total);
        $this->assertMoneyEURCents(-29998, $order->getTotal());
        $this->assertEquals('2018-0000-0001', $order->number);
        $this->assertNull($order->mollie_payment_id);

        Event::assertDispatched(OrderCreated::class, function ($e) use ($order) {
            return $e->order->is($order);
        });

        $order->processPayment();

        $this->assertDispatchedOrderProcessed($order);

        $this->assertNull($order->mollie_payment_id);
        $this->assertEquals(null, $order->mollie_payment_status);

        $this->assertMoneyEURCents(29998, $user->credit('EUR')->money());
    }

    /**
     * @test
     */
    public function canCreateOrderFromOrderItemsWhenTotalIsPaidByCreditAndOwnerHasNoMandate()
    {
        Carbon::setTestNow(Carbon::parse('2018-01-01'));
        Event::fake();
        $user = User::factory()->create(); // user without subscription/mandate
        $user->addCredit(new Money(29998, new Currency('EUR')));

        OrderItemFactory::new()->times(2)->create([
            'orderable_type' => null,
            'orderable_id' => null,
            'process_at' => now()->subMinute(), // sub minute so we're sure it's ready to be processed
            'owner_id' => $user->id,
            'owner_type' => get_class($user),
            'currency' => 'EUR',
            'quantity' => 1,
            'unit_price' => 12345, // includes vat
            'tax_percentage' => 21.5,
        ]);

        $order = Cashier::$orderModel::createFromItems(Cashier::$orderItemModel::all());

        $this->assertEquals(2, $order->items()->count());

        $this->assertEquals($user->id, $order->owner_id);
        $this->assertEquals(User::class, $order->owner_type);
        $this->assertEquals('EUR', $order->currency);
        $this->assertEquals(24690, $order->subtotal);
        $this->assertMoneyEURCents(24690, $order->getSubtotal());
        $this->assertEquals(5308, $order->tax);
        $this->assertMoneyEURCents(5308, $order->getTax());
        $this->assertEquals(29998, $order->total);
        $this->assertMoneyEURCents(29998, $order->getTotal());
        $this->assertEquals(29998, $order->refresh()->total_due);
        $this->assertMoneyEURCents(29998, $order->getTotalDue());
        $this->assertEquals('2018-0000-0001', $order->number);
        $this->assertNull($order->mollie_payment_id);

        Event::assertDispatched(OrderCreated::class, function ($e) use ($order) {
            return $e->order->is($order);
        });

        $order->processPayment();

        $this->assertDispatchedOrderProcessed($order);

        $this->assertNull($order->mollie_payment_id);
        $this->assertEquals(null, $order->mollie_payment_status);

        $this->assertMoneyEURCents(0, $user->credit('EUR')->money());
    }

    /** @test */
    public function canCreateProcessedOrderFromItems()
    {
        Event::fake();

        $user = User::factory()->create([
            'id' => 2,
            'mollie_customer_id' => $this->getMandatedCustomerId(),
        ]);

        $items = $user->orderItems()->saveMany(
            OrderItemFactory::new()->times(3)->unprocessed()->unlinked()->make()
        );

        $items->each(function ($item) {
            $this->assertFalse($item->isProcessed());
        });

        $order = Cashier::$orderModel::createProcessedFromItems($items, [
            'mollie_payment_id' => 'tr_123456',
            'mollie_payment_status' => PaymentStatus::STATUS_PAID,
        ]);

        $this->assertNotNull($order);
        $this->assertInstanceOf(Cashier::$orderModel, $order);

        $this->assertTrue($order->isProcessed());

        $this->assertEquals('tr_123456', $order->mollie_payment_id);
        $this->assertEquals(PaymentStatus::STATUS_PAID, $order->mollie_payment_status);

        $items->each(function ($item) {
            $this->assertTrue($item->isProcessed());
        });

        $this->assertDispatchedOrderProcessed($order);
    }

    /** @test */
    public function findByMolliePaymentIdWorks()
    {
        $this->assertNull(Cashier::$orderModel::findByMolliePaymentId('tr_xxxxx1234dummy'));

        $order = OrderFactory::new()->create(['mollie_payment_id' => 'tr_xxxxx1234dummy']);
        $otherOrder = OrderFactory::new()->create(['mollie_payment_id' => 'tr_wrong_order']);

        $found = Cashier::$orderModel::findByMolliePaymentId('tr_xxxxx1234dummy');

        $this->assertTrue($found->is($order));
        $this->assertTrue($found->isNot($otherOrder));
    }

    /** @test */
    public function findByMolliePaymentIdOrFailThrowsAnExceptionIfNotFound()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        Cashier::$orderModel::findByMolliePaymentIdOrFail('tr_xxxxx1234dummy');
    }

    /** @test */
    public function findByMolliePaymentIdOrFailWorks()
    {
        $order = OrderFactory::new()->create(['mollie_payment_id' => 'tr_xxxxx1234dummy']);
        $otherOrder = OrderFactory::new()->create(['mollie_payment_id' => 'tr_wrong_order']);

        $found = Cashier::$orderModel::findByMolliePaymentIdOrFail('tr_xxxxx1234dummy');

        $this->assertTrue($found->is($order));
        $this->assertTrue($found->isNot($otherOrder));
    }

    /**
     * @test
     * @group generate_new_invoice_template
     */
    public function generateNewExampleInvoice()
    {
        $user = User::factory()->create(['extra_billing_information' => 'Some dummy extra billing information']);
        $user->addCredit(new Money(500, new Currency('EUR')));
        $items = OrderItemFactory::new()->times(2)->unlinked()->EUR()->create([
            'owner_id' => $user->id,
            'owner_type' => User::class,
            'quantity' => 2,
        ]);
        $order = Cashier::$orderModel::createFromItems($items);

        $invoice = $order->invoice('2019-0000-0001', Carbon::parse('2019-05-06'));

        $filename = __DIR__ . '/../../example_invoice_output.html';
        $some_content = 'Invoice dummy';

        if (collect($this->getGroups())->contains('generate_new_invoice_template')) {
            $this->assertFileIsWritable($filename);

            if (is_writable($filename)) {
                if (!$handle = fopen($filename, 'w')) {
                    echo "Cannot open file ($filename)";
                    exit;
                }

                if (fwrite($handle, $invoice->view()->render()) === false) {
                    echo "Cannot write to file ($filename)";
                    exit;
                }

                echo "Success, wrote ($some_content) to file ($filename)";

                fclose($handle);
            } else {
                $this->fail('Cannot write example invoice to ' . $filename);
            }
        }
        $this->assertTrue(true, 'Unable to generate dummy invoice.');
    }

    /**
     * Create a basic subscription
     *
     * @return Subscription
     */
    private function createMonthlySubscription()
    {
        return SubscriptionFactory::new()->create([
            'owner_id' => 2,
            'owner_type' => User::class,
            'plan' => 'monthly-10-1',
            'cycle_ends_at' => now(),
        ]);
    }

    protected function assertDispatchedOrderProcessed(Order $order)
    {
        Event::assertDispatched(OrderProcessed::class, function ($event) use ($order) {
            return $order->is($event->order);
        });
    }
}
