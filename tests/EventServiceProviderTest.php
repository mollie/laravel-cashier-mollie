<?php

namespace Laravel\Cashier\Tests;

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\OrderInvoiceAvailable;
use Laravel\Cashier\Events\OrderPaymentPaid;
use Laravel\Cashier\Tests\Database\Factories\OrderFactory;
use PHPUnit\Framework\Attributes\Test;

class EventServiceProviderTest extends BaseTestCase
{
    #[Test]
    public function itIsWiredUpAndFiring()
    {
        Event::fake(OrderInvoiceAvailable::class);

        $event = new OrderPaymentPaid(
            OrderFactory::new()->make(),
            $this->mock(Cashier::$paymentModel)
        );

        Event::dispatch($event);

        Event::assertDispatched(OrderInvoiceAvailable::class, function ($e) use ($event) {
            return $e->order === $event->order;
        });
    }
}
