<?php

namespace Laravel\Cashier\Tests\Order;

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\FirstPaymentPaid;
use Laravel\Cashier\Events\OrderInvoiceAvailable;
use Laravel\Cashier\Events\OrderPaymentPaid;
use Laravel\Cashier\Order\OrderInvoiceSubscriber;
use Laravel\Cashier\Payment as CashierPayment;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderFactory;
use Mollie\Api\Resources\Payment;

class OrderInvoiceSubscriberTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->subscriber = new OrderInvoiceSubscriber;
    }

    /** @test */
    public function itHandlesTheFirstPaymentPaidEvent()
    {
        $this->assertItHandlesEvent(
            new FirstPaymentPaid($this->mock(Payment::class), $this->order()),
            'handleFirstPaymentPaid'
        );
    }

    /** @test */
    public function itHandlesTheOrderPaymentPaidEvent()
    {
        $this->assertItHandlesEvent(
            new OrderPaymentPaid($this->order(), $this->mock(Cashier::$paymentModel)),
            'handleOrderPaymentPaid'
        );
    }

    private function assertItHandlesEvent($event, $methodName)
    {
        Event::fake(OrderInvoiceAvailable::class);

        (new OrderInvoiceSubscriber)->$methodName($event);

        Event::assertDispatched(OrderInvoiceAvailable::class, function ($e) use ($event) {
            return $e->order === $event->order;
        });
    }

    private function order()
    {
        return OrderFactory::new()->make();
    }
}
