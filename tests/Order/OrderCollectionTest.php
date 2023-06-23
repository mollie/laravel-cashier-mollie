<?php

namespace Laravel\Cashier\Tests\Order;

use Laravel\Cashier\Order\Invoice;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderFactory;
use Laravel\Cashier\Tests\Fixtures\User;

class OrderCollectionTest extends BaseTestCase
{
    /** @test */
    public function canGetInvoices()
    {
        $this->withPackageMigrations();
        $user = User::factory()->create();
        $orders = $user->orders()->saveMany(OrderFactory::new()->times(2)->make());

        $invoices = $orders->invoices();

        $this->assertCount(2, $invoices);
        $this->assertInstanceOf(Invoice::class, $invoices->first());
    }
}
