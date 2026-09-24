<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Refunds;

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\RefundInitiated;
use Laravel\Cashier\Events\RefundProcessed;
use Laravel\Cashier\Mollie\Contracts\CreateMollieRefund;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Refunds\RefundBuilder;
use Laravel\Cashier\Refunds\RefundItem;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;
use Laravel\Cashier\Tests\Fixtures\ThrowingRefundOrderItem;
use LogicException;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Refund as MollieRefund;
use Mollie\Api\Types\RefundStatus as MollieRefundStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

class RefundsBuilderTest extends BaseTestCase
{
    #[Test]
    public function can_create_a_refund_for_a_complete_order(): void
    {
        Event::fake();
        $this->mock(CreateMollieRefund::class, function (CreateMollieRefund $mock) {
            $mollieRefund = new MollieRefund(new MollieApiClient);
            $mollieRefund->id = 're_dummy_refund_id';
            $mollieRefund->status = MollieRefundStatus::PENDING;
            $mock->shouldReceive('execute')->with('tr_dummy_payment_id', [
                'amount' => [
                    'value' => '22.00',
                    'currency' => 'EUR',
                ],
            ])->once()->andReturn($mollieRefund);
        });

        $user = $this->getUser();

        $orderItems = $user->orderItems()->createMany([
            OrderItemFactory::new()->make([
                'unit_price' => 1000,
                'tax_percentage' => 10,
                'quantity' => 1,
            ])->toArray(),
            OrderItemFactory::new()->make([
                'unit_price' => 500,
                'tax_percentage' => 10,
                'quantity' => 2,
            ])->toArray(),
        ]);

        $order = Cashier::$orderModel::createProcessedFromItems(new OrderItemCollection($orderItems));
        $order->mollie_payment_status = 'paid';
        $order->mollie_payment_id = 'tr_dummy_payment_id';
        $this->assertMoneyEURCents(2200, $order->getTotalDue());

        $refundBuilder = RefundBuilder::forWholeOrder($order);
        $refund = $refundBuilder->create();

        $this->assertInstanceOf(Cashier::$refundModel, $refund);
        $this->assertEquals('re_dummy_refund_id', $refund->mollie_refund_id);
        $this->assertEquals(MollieRefundStatus::PENDING, $refund->mollie_refund_status);
        $this->assertNull($refund->order_id);

        $refundItems = $refund->items;
        $this->assertCount(2, $refundItems);

        /** @var RefundItem $itemA */
        $itemA = $refundItems->first(function (RefundItem $item) {
            return (int) $item->quantity === 1;
        });

        $this->assertEquals($itemA->unit_price, 1000);
        $this->assertEquals($itemA->tax_percentage, 10);
        $this->assertEquals($itemA->currency, 'EUR');

        /** @var RefundItem $itemB */
        $itemB = $refundItems->first(function (RefundItem $item) {
            return (int) $item->quantity === 2;
        });
        $this->assertEquals($itemB->unit_price, 500);
        $this->assertEquals($itemB->tax_percentage, 10);
        $this->assertEquals($itemB->currency, 'EUR');

        Event::assertDispatched(RefundInitiated::class, function (RefundInitiated $event) use ($refund) {
            return $event->refund->is($refund);
        });
    }

    #[Test]
    public function only_refunds_the_amount_charged_through_mollie_when_credit_was_used(): void
    {
        Event::fake();

        // Order total EUR 22.00, of which EUR 5.00 came from the owner's credit balance.
        // Only the EUR 17.00 that was actually charged can be refunded through Mollie.
        $this->mock(CreateMollieRefund::class, function (CreateMollieRefund $mock) {
            $mollieRefund = new MollieRefund(new MollieApiClient);
            $mollieRefund->id = 're_dummy_refund_id';
            $mollieRefund->status = MollieRefundStatus::PENDING;
            $mock->shouldReceive('execute')->with('tr_dummy_payment_id', [
                'amount' => [
                    'value' => '17.00',
                    'currency' => 'EUR',
                ],
            ])->once()->andReturn($mollieRefund);
        });

        $user = $this->getUser();
        $order = $this->createPaidOrderUsingCredit($user);

        $this->assertMoneyEURCents(2200, $order->getTotal());
        $this->assertMoneyEURCents(1700, $order->getTotalDue());
        $this->assertMoneyEURCents(500, $order->getCreditUsed());

        $refund = RefundBuilder::forWholeOrder($order)->create();

        // The refund still reverses the full order value for bookkeeping purposes.
        $this->assertEquals(2200, $refund->total);
        $this->assertCount(2, $refund->items);

        // No credit is moved yet - that happens when Mollie confirms the refund.
        $this->assertFalse($user->fresh()->hasCredit('EUR'));

        Event::assertDispatched(RefundInitiated::class, function (RefundInitiated $event) use ($refund) {
            return $event->refund->is($refund);
        });
    }

    #[Test]
    public function caps_a_follow_up_refund_for_the_remaining_order_value_at_what_mollie_can_still_refund(): void
    {
        Event::fake();

        // EUR 10.00 has already been refunded and processed. Reversing the remaining
        // EUR 12.00 can only charge back the EUR 7.00 left of the EUR 17.00 payment; the
        // other EUR 5.00 was paid from credit and goes back to the balance.
        $this->mock(CreateMollieRefund::class, function (CreateMollieRefund $mock) {
            $mock->shouldReceive('execute')->with('tr_dummy_payment_id', [
                'amount' => [
                    'value' => '7.00',
                    'currency' => 'EUR',
                ],
            ])->once()->andReturnUsing(function () {
                $mollieRefund = new MollieRefund(new MollieApiClient);
                $mollieRefund->id = 're_dummy_refund_id';
                $mollieRefund->status = MollieRefundStatus::PENDING;

                return $mollieRefund;
            });
        });

        $user = $this->getUser();
        $order = $this->createPaidOrderUsingCredit($user);
        $orderItem = $order->items->first();
        $order->update(['amount_refunded' => 1000]);

        $this->assertMoneyEURCents(700, $order->getTotalDueRefundable());
        $this->assertMoneyEURCents(1200, $order->getTotalRefundable());

        RefundBuilder::forOrder($order)
            ->addItemFromOrderItem($orderItem, [
                'unit_price' => 1200,
                'tax_percentage' => 0,
                'quantity' => 1,
            ])
            ->create()
            ->handleProcessed();

        // The order is now fully reversed - no more, no less - and the credit is back.
        $this->assertMoneyEURCents(2200, $order->refresh()->getAmountRefunded());
        $this->assertMoneyEURCents(0, $order->getTotalRefundable());
        $this->assertEquals(500, $user->fresh()->credit('EUR')->value);
    }

    #[Test]
    public function rejects_a_refund_that_reverses_more_than_the_order_has_left(): void
    {
        Event::fake();

        // Mollie would accept a further EUR 7.00, so the Mollie amount alone does not
        // catch this. Recording EUR 22.00 of items against an order with EUR 12.00 left
        // would push amount_refunded to EUR 32.00 on a EUR 22.00 order.
        $this->mock(CreateMollieRefund::class, function (CreateMollieRefund $mock) {
            $mock->shouldReceive('execute')->once()->andReturnUsing(function () {
                $mollieRefund = new MollieRefund(new MollieApiClient);
                $mollieRefund->id = 're_dummy_refund_id';
                $mollieRefund->status = MollieRefundStatus::PENDING;

                return $mollieRefund;
            });
        });

        $user = $this->getUser();
        $order = $this->createPaidOrderUsingCredit($user);
        $orderItem = $order->items->first();

        RefundBuilder::forOrder($order)
            ->addItemFromOrderItem($orderItem, [
                'unit_price' => 1000,
                'tax_percentage' => 0,
                'quantity' => 1,
            ])
            ->create()
            ->handleProcessed();

        $this->assertMoneyEURCents(1000, $order->refresh()->getAmountRefunded());

        try {
            $order->refundCompletely();
            $this->fail('Refunding more than the order has left was not rejected.');
        } catch (LogicException $exception) {
            $this->assertStringContainsString(
                'only 12.00 EUR of the order value is still refundable',
                $exception->getMessage()
            );
        }

        // Nothing was recorded, so the bookkeeping cannot exceed the order value.
        $this->assertMoneyEURCents(1000, $order->refresh()->getAmountRefunded());
        $this->assertSame(1, Cashier::$refundModel::count());
    }

    #[Test]
    public function completes_a_credit_only_follow_up_refund_without_calling_mollie_again(): void
    {
        Event::fake();

        $this->mock(CreateMollieRefund::class, function (CreateMollieRefund $mock) {
            $mollieRefund = new MollieRefund(new MollieApiClient);
            $mollieRefund->id = 're_dummy_refund_id';
            $mollieRefund->status = MollieRefundStatus::PENDING;
            $mock->shouldReceive('execute')->with('tr_dummy_payment_id', [
                'amount' => [
                    'value' => '17.00',
                    'currency' => 'EUR',
                ],
            ])->once()->andReturn($mollieRefund);
        });

        $user = $this->getUser();
        $order = $this->createPaidOrderUsingCredit($user);
        $orderItem = $order->items->first();

        $firstRefund = RefundBuilder::forOrder($order)
            ->addItemFromOrderItem($orderItem, [
                'unit_price' => 2000,
                'tax_percentage' => 0,
                'quantity' => 1,
            ])
            ->create();

        $firstRefund->handleProcessed();

        $this->assertMoneyEURCents(2000, $order->refresh()->getAmountRefunded());
        $this->assertEquals(300, $user->fresh()->credit('EUR')->value);

        $creditBeforeSecondRefund = $user->fresh()->credit('EUR')->value;

        $secondRefund = RefundBuilder::forOrder($order)
            ->addItemFromOrderItem($orderItem, [
                'unit_price' => 200,
                'tax_percentage' => 0,
                'quantity' => 1,
            ])
            ->create();

        $this->assertStringStartsWith('local_', $secondRefund->mollie_refund_id);
        $this->assertEquals(MollieRefundStatus::REFUNDED, $secondRefund->mollie_refund_status);
        $this->assertMoneyEURCents(2200, $order->refresh()->getAmountRefunded());
        $this->assertEquals(500, $user->fresh()->credit('EUR')->value);
        $this->assertEquals(200, $user->fresh()->credit('EUR')->value - $creditBeforeSecondRefund);

        Cashier::$refundModel::find($secondRefund->id)->handleProcessed();
        Cashier::$refundModel::find($secondRefund->id)->handleProcessed();

        $this->assertMoneyEURCents(2200, $order->refresh()->getAmountRefunded());
        $this->assertEquals(500, $user->fresh()->credit('EUR')->value);
        Event::assertDispatchedTimes(RefundInitiated::class, 2);
        Event::assertDispatchedTimes(RefundProcessed::class, 2);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('only 0.00 EUR of the order value is still refundable');

        RefundBuilder::forOrder($order)
            ->addItemFromOrderItem($orderItem, [
                'unit_price' => 100,
                'tax_percentage' => 0,
                'quantity' => 1,
            ])
            ->create();
    }

    #[Test]
    #[DataProvider('localRefundEventClasses')]
    public function rolls_back_a_local_refund_when_an_event_listener_fails(string $eventClass): void
    {
        $user = $this->getUser();
        [$order, $orderItem] = $this->createOrderWithPartiallyProcessedRefund($user);
        $builder = $this->creditOnlyFollowUpBuilder($order, $orderItem);
        $shouldThrow = true;

        Event::listen($eventClass, function () use (&$shouldThrow) {
            if ($shouldThrow) {
                $shouldThrow = false;

                throw new RuntimeException('Local refund event listener failed.');
            }
        });

        try {
            $builder->create();
            $this->fail('The event listener exception was not thrown.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Local refund event listener failed.', $exception->getMessage());
        }

        $this->assertSame(1, Cashier::$refundModel::count());
        $this->assertSame(2, Cashier::$orderModel::count());
        $this->assertMoneyEURCents(2000, $order->refresh()->getAmountRefunded());
        $this->assertSame(300, $user->fresh()->credit('EUR')->value);

        $refund = $builder->create();

        $this->assertEquals(MollieRefundStatus::REFUNDED, $refund->mollie_refund_status);
        $this->assertSame(2, Cashier::$refundModel::count());
        $this->assertMoneyEURCents(2200, $order->refresh()->getAmountRefunded());
        $this->assertSame(500, $user->fresh()->credit('EUR')->value);
    }

    public static function localRefundEventClasses(): iterable
    {
        yield 'initiated event' => [RefundInitiated::class];
        yield 'processed event' => [RefundProcessed::class];
    }

    #[Test]
    public function rolls_back_a_local_refund_when_an_item_hook_fails(): void
    {
        $originalOrderItemModel = Cashier::$orderItemModel;
        Cashier::useOrderItemModel(ThrowingRefundOrderItem::class);

        try {
            $user = $this->getUser();
            [$order, $orderItem] = $this->createOrderWithPartiallyProcessedRefund($user);
            $builder = $this->creditOnlyFollowUpBuilder($order, $orderItem);
            ThrowingRefundOrderItem::$throwOnRefund = true;

            try {
                $builder->create();
                $this->fail('The item hook exception was not thrown.');
            } catch (RuntimeException $exception) {
                $this->assertSame('Refund item hook failed.', $exception->getMessage());
            }

            $this->assertSame(1, Cashier::$refundModel::count());
            $this->assertSame(2, Cashier::$orderModel::count());
            $this->assertMoneyEURCents(2000, $order->refresh()->getAmountRefunded());
            $this->assertSame(300, $user->fresh()->credit('EUR')->value);

            ThrowingRefundOrderItem::$throwOnRefund = false;
            $refund = $builder->create();

            $this->assertEquals(MollieRefundStatus::REFUNDED, $refund->mollie_refund_status);
            $this->assertSame(2, Cashier::$refundModel::count());
            $this->assertMoneyEURCents(2200, $order->refresh()->getAmountRefunded());
            $this->assertSame(500, $user->fresh()->credit('EUR')->value);
        } finally {
            ThrowingRefundOrderItem::$throwOnRefund = false;
            Cashier::useOrderItemModel($originalOrderItemModel);
        }
    }

    #[Test]
    public function rejects_a_stale_competing_builder_after_the_remaining_credit_is_refunded(): void
    {
        Event::fake();

        $user = $this->getUser();
        [$order, $orderItem] = $this->createOrderWithPartiallyProcessedRefund($user);
        $firstBuilder = $this->creditOnlyFollowUpBuilder($order, $orderItem);
        $staleCompetingBuilder = $this->creditOnlyFollowUpBuilder(
            Cashier::$orderModel::findOrFail($order->id),
            $orderItem
        );

        $firstBuilder->create();

        try {
            $staleCompetingBuilder->create();
            $this->fail('The stale competing builder was not rejected.');
        } catch (LogicException $exception) {
            $this->assertStringContainsString('There is nothing left to refund through Mollie', $exception->getMessage());
        }

        $this->assertSame(2, Cashier::$refundModel::count());
        $this->assertSame(3, Cashier::$orderModel::count());
        $this->assertMoneyEURCents(2200, $order->refresh()->getAmountRefunded());
        $this->assertSame(500, $user->fresh()->credit('EUR')->value);
    }

    #[Test]
    public function cannot_refund_an_order_that_was_paid_entirely_with_credit(): void
    {
        Event::fake();

        $this->mock(CreateMollieRefund::class, function (CreateMollieRefund $mock) {
            $mock->shouldNotReceive('execute');
        });

        $user = $this->getUser();
        $order = $this->createPaidOrderUsingCredit($user);
        $order->update(['balance_before' => 2200, 'credit_used' => 2200, 'total_due' => 0]);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('There is nothing left to refund through Mollie');

        RefundBuilder::forWholeOrder($order)->create();
    }

    #[Test]
    public function does_not_ask_mollie_to_refund_more_than_was_charged(): void
    {
        Event::fake();

        // Stands in for Mollie's own validation: a payment of EUR 17.00 cannot be refunded
        // for more than EUR 17.00. Asking for more is what issue #328 reported, and Mollie
        // answers it with a 422 "The specified amount cannot be refunded."
        $this->mock(CreateMollieRefund::class, function (CreateMollieRefund $mock) {
            $mock->shouldReceive('execute')->andReturnUsing(function ($paymentId, $payload) {
                $amountChargedThroughMollie = 1700;
                $requested = (int) round(((float) $payload['amount']['value']) * 100);

                if ($requested > $amountChargedThroughMollie) {
                    throw new RuntimeException(
                        'Error executing API call (422: Unprocessable Entity): ' .
                        'The specified amount cannot be refunded.'
                    );
                }

                $mollieRefund = new MollieRefund(new MollieApiClient);
                $mollieRefund->id = 're_dummy_refund_id';
                $mollieRefund->status = MollieRefundStatus::PENDING;

                return $mollieRefund;
            });
        });

        $user = $this->getUser();
        $order = $this->createPaidOrderUsingCredit($user);

        $refund = RefundBuilder::forWholeOrder($order)->create();

        $this->assertEquals('re_dummy_refund_id', $refund->mollie_refund_id);
        $this->assertEquals(2200, $refund->total);
    }

    /**
     * An EUR 22.00 order, paid with EUR 5.00 credit and EUR 17.00 through Mollie.
     */
    protected function createPaidOrderUsingCredit($user)
    {
        $orderItems = $user->orderItems()->createMany([
            OrderItemFactory::new()->make([
                'unit_price' => 1000,
                'tax_percentage' => 10,
                'quantity' => 1,
            ])->toArray(),
            OrderItemFactory::new()->make([
                'unit_price' => 500,
                'tax_percentage' => 10,
                'quantity' => 2,
            ])->toArray(),
        ]);

        $order = Cashier::$orderModel::createProcessedFromItems(new OrderItemCollection($orderItems));

        $order->update([
            'mollie_payment_status' => 'paid',
            'mollie_payment_id' => 'tr_dummy_payment_id',
            'balance_before' => 500,
            'credit_used' => 500,
            'total_due' => 1700,
        ]);

        return $order;
    }

    protected function createOrderWithPartiallyProcessedRefund($user): array
    {
        $this->mock(CreateMollieRefund::class, function (CreateMollieRefund $mock) {
            $mollieRefund = new MollieRefund(new MollieApiClient);
            $mollieRefund->id = 're_dummy_refund_id';
            $mollieRefund->status = MollieRefundStatus::PENDING;
            $mock->shouldReceive('execute')->once()->andReturn($mollieRefund);
        });

        $order = $this->createPaidOrderUsingCredit($user);
        $orderItem = $order->items->first();

        RefundBuilder::forOrder($order)
            ->addItemFromOrderItem($orderItem, [
                'unit_price' => 2000,
                'tax_percentage' => 0,
                'quantity' => 1,
            ])
            ->create()
            ->handleProcessed();

        $order->refresh();

        return [$order, $orderItem];
    }

    protected function creditOnlyFollowUpBuilder($order, $orderItem): RefundBuilder
    {
        return RefundBuilder::forOrder($order)
            ->addItemFromOrderItem($orderItem, [
                'unit_price' => 200,
                'tax_percentage' => 0,
                'quantity' => 1,
            ]);
    }
}
