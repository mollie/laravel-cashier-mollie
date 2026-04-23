<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Refunds;

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\RefundFailed;
use Laravel\Cashier\Events\RefundProcessed;
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
}
