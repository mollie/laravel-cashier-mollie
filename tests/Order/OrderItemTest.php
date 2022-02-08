<?php

namespace Laravel\Cashier\Tests\Order;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Tests\BaseTestCase;

class OrderItemTest extends BaseTestCase
{
    public function testGetSubtotalAttribute()
    {
        $item = new Cashier::$orderItemModel;
        $item->currency = 'EUR';
        $item->quantity = 4;
        $item->unit_price = 110;
        $item->tax_percentage = 21.50; // should be excluded from calculation

        // 440 = (4 * 110 - 200)
        $this->assertEquals(440, $item->getSubtotalAttribute());
        $this->assertEquals(440, $item->subtotal);
        $this->assertMoneyEURCents(440, $item->getSubtotal());
    }

    public function testGetTaxAttribute()
    {
        $item = new Cashier::$orderItemModel;
        $item->currency = 'EUR';
        $item->quantity = 4;
        $item->unit_price = 110;
        $item->tax_percentage = 21.5;

        // 94.6 = (4 * 110) * (21.5 / 100)
        $this->assertEquals(95, $item->getTaxAttribute());
        $this->assertEquals(95, $item->tax);
        $this->assertMoneyEURCents(95, $item->getTax());
    }

    public function testGetTotalAttribute()
    {
        $item = new Cashier::$orderItemModel;
        $item->currency = 'EUR';
        $item->quantity = 4;
        $item->unit_price = 110;
        $item->tax_percentage = 21.5;

        // 534.6 = 4 * 110 + (4 * 110) * (21.5 / 100)
        $this->assertEquals(535, $item->getTotalAttribute());
        $this->assertEquals(535, $item->total);
        $this->assertMoneyEURCents(535, $item->getTotal());
    }

    public function testGetAttributesAsMoney()
    {
        $item = new Cashier::$orderItemModel;
        $item->currency = 'EUR';
        $item->quantity = 4;
        $item->unit_price = 110;
        $item->tax_percentage = 21.5;

        $this->assertMoneyEURCents(535, $item->getTotal());
        $this->assertMoneyEURCents(110, $item->getUnitPrice());
        $this->assertMoneyEURCents(95, $item->getTax());
    }

    public function testScopeProcessed()
    {
        $this->withPackageMigrations();

        factory(Cashier::$orderItemModel, 3)->create([
            'order_id' => null,
        ]);
        factory(Cashier::$orderItemModel, 2)->create([
            'order_id' => 1,
        ]);

        $this->assertEquals(2, Cashier::$orderItemModel::processed()->count());
        $this->assertEquals(2, Cashier::$orderItemModel::processed(true)->count());
        $this->assertEquals(3, Cashier::$orderItemModel::processed(false)->count());
    }

    public function testScopeUnprocessed()
    {
        $this->withPackageMigrations();

        factory(Cashier::$orderItemModel, 3)->create([
            'order_id' => null,
        ]);
        factory(Cashier::$orderItemModel, 2)->create([
            'order_id' => 1,
        ]);

        $this->assertEquals(3, Cashier::$orderItemModel::unprocessed()->count());
        $this->assertEquals(3, Cashier::$orderItemModel::unprocessed(true)->count());
        $this->assertEquals(2, Cashier::$orderItemModel::unprocessed(false)->count());
    }

    public function testScopeShouldProcess()
    {
        $this->withPackageMigrations();

        factory(Cashier::$orderItemModel, 2)->create([
            'order_id' => 1,
            'process_at' => now()->subHour(),
        ]);
        factory(Cashier::$orderItemModel, 2)->create([
            'order_id' => null,
            'process_at' => now()->addDay(),
        ]);
        factory(Cashier::$orderItemModel, 3)->create([
            'order_id' => null,
            'process_at' => now()->subHour(),
        ]);

        $this->assertEquals(3, Cashier::$orderItemModel::shouldProcess()->count());
    }

    public function testScopeDue()
    {
        $this->withPackageMigrations();

        factory(Cashier::$orderItemModel, 2)->create([
            'process_at' => now()->subHour(),
        ]);
        factory(Cashier::$orderItemModel, 3)->create([
            'process_at' => now()->addMinutes(5),
        ]);

        $this->assertEquals(2, Cashier::$orderItemModel::due()->count());
    }

    public function testNewCollection()
    {
        $collection = factory(Cashier::$orderItemModel, 2)->make();
        $this->assertInstanceOf(OrderItemCollection::class, $collection);
    }

    public function testIsProcessed()
    {
        $unprocessedItem = factory(Cashier::$orderItemModel)->make(['order_id' => null]);
        $processedItem = factory(Cashier::$orderItemModel)->make(['order_id' => 1]);

        $this->assertFalse($unprocessedItem->isProcessed());
        $this->assertTrue($unprocessedItem->isProcessed(false));

        $this->assertTrue($processedItem->isProcessed());
        $this->assertFalse($processedItem->isProcessed(false));
    }
}
