<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Refunds;

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\RefundFailed;
use Laravel\Cashier\Events\RefundProcessed;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Refunds\Refund;
use Laravel\Cashier\Refunds\RefundItemCollection;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;
use Laravel\Cashier\Tests\Database\Factories\RefundFactory;
use Mollie\Api\Types\RefundStatus as MollieRefundStatus;
use PHPUnit\Framework\Attributes\Test;

class RefundTest extends BaseTestCase
{
    #[Test]
    public function canHandleProcessedMollieRefund()
    {
        Event::fake();

        $user = $this->getCustomerUser();
        $originalOrderItems = OrderItemFactory::new()->times(2)->create();
        $originalOrder = Cashier::$orderModel::createProcessedFromItems($originalOrderItems);
        $this->assertMoneyEURCents(0, $originalOrder->getAmountRefunded());

        /** @var Refund $refund */
        $refund = RefundFactory::new()->create([
            'total' => 29524,
            'currency' => 'EUR',
        ]);

        $refund->items()->saveMany(
            RefundItemCollection::makeFromOrderItemCollection($originalOrderItems)
        );
        $this->assertEquals(MollieRefundStatus::PENDING, $refund->mollie_refund_status);

        $refund = $refund->handleProcessed();

        $this->assertNotNull($refund->order_id);
        $this->assertEquals(MollieRefundStatus::REFUNDED, $refund->mollie_refund_status);
        $this->assertMoneyEURCents(29524, $originalOrder->refresh()->getAmountRefunded());

        $order = $refund->order;
        $this->assertTrue($order->isNot($originalOrder));
        $this->assertTrue($order->isProcessed());
        $this->assertEquals(-29524, $order->total_due);
        $this->assertInstanceOf(Cashier::$refundItemModel, $order->items->first()->orderable);

        Event::assertDispatched(RefundProcessed::class, function (RefundProcessed $event) use ($refund) {
            return $event->refund->is($refund);
        });
    }

    #[Test]
    public function handlesDuplicateProcessedRefundFromStaleRefundOnce()
    {
        Event::fake();

        $this->getCustomerUser();
        $originalOrderItems = OrderItemFactory::new()->times(2)->create();
        $originalOrder = Cashier::$orderModel::createProcessedFromItems($originalOrderItems);

        /** @var Refund $refund */
        $refund = RefundFactory::new()->create([
            'original_order_id' => $originalOrder->id,
            'total' => 29524,
            'currency' => 'EUR',
        ]);

        $refund->items()->saveMany(
            RefundItemCollection::makeFromOrderItemCollection($originalOrderItems)
        );

        $firstRefundInstance = Cashier::$refundModel::find($refund->id);
        $secondRefundInstance = Cashier::$refundModel::find($refund->id);

        $firstRefundInstance->handleProcessed();
        $secondRefundInstance->handleProcessed();

        $refund->refresh();
        $this->assertEquals(MollieRefundStatus::REFUNDED, $refund->mollie_refund_status);
        $this->assertNotNull($refund->order_id);
        $this->assertEquals(2, Cashier::$orderModel::count());
        $this->assertMoneyEURCents(29524, $originalOrder->refresh()->getAmountRefunded());
        Event::assertDispatchedTimes(RefundProcessed::class, 1);
    }

    #[Test]
    public function canHandleFailedMollieRefund()
    {
        Event::fake();

        $user = $this->getCustomerUser();
        $originalOrderItems = OrderItemFactory::new()->times(2)->create();
        $originalOrder = Cashier::$orderModel::createProcessedFromItems($originalOrderItems);
        $this->assertMoneyEURCents(0, $originalOrder->getAmountRefunded());

        /** @var Refund $refund */
        $refund = RefundFactory::new()->create([
            'total' => 29524,
            'currency' => 'EUR',
        ]);

        $refund->items()->saveMany(
            RefundItemCollection::makeFromOrderItemCollection($originalOrderItems)
        );
        $this->assertEquals(MollieRefundStatus::PENDING, $refund->mollie_refund_status);

        $refund = $refund->handleFailed();

        $this->assertNull($refund->order_id);
        $this->assertEquals(MollieRefundStatus::FAILED, $refund->mollie_refund_status);
        $this->assertMoneyEURCents(0, $originalOrder->refresh()->getAmountRefunded());

        $this->assertNull($refund->order);

        Event::assertDispatched(RefundFailed::class, function (RefundFailed $event) use ($refund) {
            return $event->refund->is($refund);
        });
    }

    #[Test]
    public function handlesDuplicateFailedRefundFromStaleRefundOnce()
    {
        Event::fake();

        $this->getCustomerUser();
        $originalOrderItems = OrderItemFactory::new()->times(2)->create();
        $originalOrder = Cashier::$orderModel::createProcessedFromItems($originalOrderItems);

        /** @var Refund $refund */
        $refund = RefundFactory::new()->create([
            'original_order_id' => $originalOrder->id,
            'total' => 29524,
            'currency' => 'EUR',
        ]);

        $refund->items()->saveMany(
            RefundItemCollection::makeFromOrderItemCollection($originalOrderItems)
        );

        $firstRefundInstance = Cashier::$refundModel::find($refund->id);
        $secondRefundInstance = Cashier::$refundModel::find($refund->id);

        $firstRefundInstance->handleFailed();
        $secondRefundInstance->handleFailed();

        $refund->refresh();
        $this->assertEquals(MollieRefundStatus::FAILED, $refund->mollie_refund_status);
        $this->assertNull($refund->order_id);
        $this->assertEquals(1, Cashier::$orderModel::count());
        $this->assertMoneyEURCents(0, $originalOrder->refresh()->getAmountRefunded());
        Event::assertDispatchedTimes(RefundFailed::class, 1);
    }

    #[Test]
    public function restoresUsedCreditWhenTheRefundIsProcessed()
    {
        Event::fake();

        $user = $this->getCustomerUser();
        $originalOrder = $this->createOrderPaidPartlyWithCredit($user);
        $this->assertFalse($user->hasCredit('EUR'));

        $refund = $this->createPendingRefundForWholeOrder($originalOrder);

        $refund->handleProcessed();

        // EUR 17.00 came back through Mollie, the EUR 5.00 paid from credit goes back
        // to the balance.
        $this->assertEquals(500, $user->fresh()->credit('EUR')->value);
        $this->assertMoneyEURCents(2200, $originalOrder->refresh()->getAmountRefunded());
        $this->assertMoneyEURCents(500, $originalOrder->getCreditUsedRestored());
    }

    #[Test]
    public function doesNotRestoreUsedCreditWhenTheRefundFails()
    {
        Event::fake();

        $user = $this->getCustomerUser();
        $originalOrder = $this->createOrderPaidPartlyWithCredit($user);

        $refund = $this->createPendingRefundForWholeOrder($originalOrder);

        $refund->handleFailed();

        // Nothing was handed back, so there is nothing to reclaim. The balance cannot be
        // left with credit for a refund that never happened, nor be pushed negative.
        $this->assertFalse($user->fresh()->hasCredit('EUR'));
        $this->assertMoneyEURCents(0, $originalOrder->refresh()->getAmountRefunded());
    }

    #[Test]
    public function doesNotRestoreUsedCreditTwiceOnDuplicateProcessedDeliveries()
    {
        Event::fake();

        $user = $this->getCustomerUser();
        $originalOrder = $this->createOrderPaidPartlyWithCredit($user);

        $refund = $this->createPendingRefundForWholeOrder($originalOrder);

        Cashier::$refundModel::find($refund->id)->handleProcessed();
        Cashier::$refundModel::find($refund->id)->handleProcessed();

        $this->assertEquals(500, $user->fresh()->credit('EUR')->value);
    }

    #[Test]
    public function restoresUsedCreditOnlyOnceAcrossSuccessivePartialRefunds()
    {
        Event::fake();

        $user = $this->getCustomerUser();
        $originalOrder = $this->createOrderPaidPartlyWithCredit($user);

        // EUR 12.00 then EUR 10.00, together reversing the full EUR 22.00 order.
        $first = $this->createPendingRefund($originalOrder, 1200);
        $second = $this->createPendingRefund($originalOrder, 1000);

        $first->handleProcessed();

        // Still within the EUR 17.00 charged through Mollie, so no credit moves yet.
        $this->assertFalse($user->fresh()->hasCredit('EUR'));

        $second->handleProcessed();

        // The EUR 5.00 paid from credit comes back exactly once.
        $this->assertEquals(500, $user->fresh()->credit('EUR')->value);
        $this->assertMoneyEURCents(2200, $originalOrder->refresh()->getAmountRefunded());
    }

    /**
     * An EUR 22.00 order, paid with EUR 5.00 credit and EUR 17.00 through Mollie.
     */
    protected function createOrderPaidPartlyWithCredit($user)
    {
        $orderItems = $user->orderItems()->createMany([
            OrderItemFactory::new()->make([
                'unit_price' => 2200,
                'tax_percentage' => 0,
                'quantity' => 1,
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

    protected function createPendingRefundForWholeOrder($originalOrder): Refund
    {
        /** @var Refund $refund */
        $refund = RefundFactory::new()->create([
            'original_order_id' => $originalOrder->id,
            'total' => 2200,
            'currency' => 'EUR',
        ]);

        $refund->items()->saveMany(
            RefundItemCollection::makeFromOrderItemCollection($originalOrder->items)
        );

        return $refund;
    }

    protected function createPendingRefund($originalOrder, int $total): Refund
    {
        /** @var Refund $refund */
        $refund = RefundFactory::new()->create([
            'original_order_id' => $originalOrder->id,
            'total' => $total,
            'currency' => 'EUR',
        ]);

        $refund->items()->saveMany(
            RefundItemCollection::makeFromOrderItemCollection(
                $originalOrder->items,
                ['unit_price' => $total, 'tax_percentage' => 0, 'quantity' => 1]
            )
        );

        return $refund;
    }
}
