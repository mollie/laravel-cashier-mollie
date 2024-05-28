<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Refunds;

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\RefundInitiated;
use Laravel\Cashier\Mollie\Contracts\CreateMollieRefund;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Refunds\RefundBuilder;
use Laravel\Cashier\Refunds\RefundItem;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Refund as MollieRefund;
use Mollie\Api\Types\RefundStatus as MollieRefundStatus;

class RefundsBuilderTest extends BaseTestCase
{
    /** @test */
    public function can_create_a_refund_for_a_complete_order(): void
    {
        Event::fake();
        $this->mock(CreateMollieRefund::class, function (CreateMollieRefund $mock) {
            $mollieRefund = new MollieRefund(new MollieApiClient);
            $mollieRefund->id = 're_dummy_refund_id';
            $mollieRefund->status = MollieRefundStatus::STATUS_PENDING;
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
        $this->assertEquals(MollieRefundStatus::STATUS_PENDING, $refund->mollie_refund_status);
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
}
