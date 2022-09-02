<?php

namespace Laravel\Cashier\Tests;

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\OrderInvoiceAvailable;
use Laravel\Cashier\Events\OrderPaymentPaid;

class EventServiceProviderTest extends BaseTestCase
{
    /** @test */
    public function itIsWiredUpAndFiring()
    {
        Event::fake(OrderInvoiceAvailable::class);

        $event = new OrderPaymentPaid(factory(Cashier::$orderModel)->make());
        Event::dispatch($event);

        Event::assertDispatched(OrderInvoiceAvailable::class, fn($e) => $e->order === $event->order);
    }
}
