<?php

namespace Laravel\Cashier\Tests\FirstPayment\Actions;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\FirstPayment\Actions\AddGenericOrderItem;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Fixtures\User;
use Money\Currency;
use Money\Money;

class AddGenericOrderItemTest extends BaseTestCase
{
    /** @test */
    public function canGetPayload()
    {
        $this->withPackageMigrations();

        $action = new AddGenericOrderItem(
            $this->getMandatedUser(true, ['tax_percentage' => 20]),
            new Money(5, new Currency('EUR')),
            1,
            'Adding a test order item'
        );

        $payload = $action->getPayload();

        $this->assertEquals([
            'handler' => AddGenericOrderItem::class,
            'unit_price' => [
                'value' => '0.05',
                'currency' => 'EUR',
            ],
            'taxPercentage' => 20,
            'description' => 'Adding a test order item',
            'quantity' => 1,
        ], $payload);
    }

    /** @test */
    public function canCreateFromPayload()
    {
        $action = AddGenericOrderItem::createFromPayload([
            'subtotal' => [
                'value' => '0.05',
                'currency' => 'EUR',
            ],
            'taxPercentage' => 20,
            'description' => 'Adding a test order item',
        ], User::factory()->make());

        $this->assertInstanceOf(AddGenericOrderItem::class, $action);
        $this->assertMoneyEURCents(5, $action->getSubtotal());
        $this->assertMoneyEURCents(6, $action->getTotal());
        $this->assertMoneyEURCents(1, $action->getTax());
        $this->assertEquals(20, $action->getTaxPercentage());
    }

    /** @test */
    public function canCreateFromPayloadWithoutTaxPercentage()
    {
        $action = AddGenericOrderItem::createFromPayload([
            'subtotal' => [
                'value' => '0.05',
                'currency' => 'EUR',
            ],
            'description' => 'Adding a test order item',
        ], User::factory()->make(['taxPercentage' => 0]));

        $this->assertInstanceOf(AddGenericOrderItem::class, $action);
        $this->assertMoneyEURCents(5, $action->getSubtotal());
        $this->assertMoneyEURCents(5, $action->getTotal());
        $this->assertMoneyEURCents(0, $action->getTax());
        $this->assertEquals(0, $action->getTaxPercentage());
    }

    /** @test */
    public function canExecute()
    {
        $this->withPackageMigrations();
        $user = User::factory()->create(['tax_percentage' => 20]);
        $this->assertFalse($user->hasCredit());

        $action = new AddGenericOrderItem(
            $user,
            new Money(5, new Currency('EUR')),
            1,
            'Adding a test order item'
        );

        $items = $action->execute();
        $item = $items->first();

        $this->assertInstanceOf(OrderItemCollection::class, $items);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Cashier::$orderItemModel, $item);
        $this->assertEquals('Adding a test order item', $item->description);
        $this->assertEquals('EUR', $item->currency);
        $this->assertEquals(5, $item->unit_price);
        $this->assertEquals(1, $item->quantity);
        $this->assertEquals(20, $item->tax_percentage);
        $this->assertNotNull($item->id); // item is persisted
        $this->assertFalse($item->isProcessed());
    }
}
