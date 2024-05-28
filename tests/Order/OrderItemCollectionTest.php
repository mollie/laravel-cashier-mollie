<?php

namespace Laravel\Cashier\Tests\Order;

use Illuminate\Support\Collection;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;
use Laravel\Cashier\Tests\Fixtures\User;

class OrderItemCollectionTest extends BaseTestCase
{
    /** @test */
    public function testCurrencies()
    {
        $collection = new OrderItemCollection([
            OrderItemFactory::new()->USD()->make(),
            OrderItemFactory::new()->USD()->make(),
            OrderItemFactory::new()->EUR()->make(),
        ]);

        $this->assertEquals(collect(['USD', 'EUR']), $collection->currencies());
    }

    /** @test */
    public function testCurrency()
    {
        $collection = new OrderItemCollection([
            OrderItemFactory::new()->USD()->make(),
            OrderItemFactory::new()->USD()->make(),
        ]);

        $this->assertEquals('USD', $collection->currency());
    }

    /** @test */
    public function testCurrencyThrowsExceptionWhenMultipleCurrenciesAreUsed()
    {
        $collection = new OrderItemCollection([
            OrderItemFactory::new()->USD()->make(),
            OrderItemFactory::new()->EUR()->make(),
        ]);

        $this->assertEquals(collect(['USD', 'EUR']), $collection->currencies());
        $this->expectException(\LogicException::class);

        $collection->currency();
    }

    /** @test */
    public function canGetTotal()
    {
        $collection = new OrderItemCollection([
            OrderItemFactory::new()->EUR()->make([
                'unit_price' => 12150,
                'quantity' => 1,
                'tax_percentage' => 10.0,
            ]),
            OrderItemFactory::new()->EUR()->make([
                'unit_price' => 12150,
                'quantity' => 2,
                'tax_percentage' => 10.0,
            ]),
        ]);

        $this->assertMoneyEURCents(40095, $collection->getTotal());
    }

    /** @test */
    public function cannotGetTotalForMultipleCurrencies()
    {
        $collection = new OrderItemCollection([
            OrderItemFactory::new()->USD()->make(),
            OrderItemFactory::new()->EUR()->make(),
        ]);

        $this->expectException(\LogicException::class);

        $collection->getTotal();
    }

    /** @test */
    public function testOwners()
    {
        User::factory(3)->create()->each(function ($owner) {
            $owner->orderItems()->saveMany(OrderItemFactory::new()->times(2)->make());
        });

        $owners = Cashier::$orderItemModel::all()->owners();
        $this->assertCount(3, $owners);

        $owners->each(function ($owner) {
            $this->assertInstanceOf(User::class, $owner);
        });
    }

    /** @test */
    public function testWhereOwners()
    {
        $item1 = OrderItemFactory::new()->make([
            'owner_id' => 1,
            'owner_type' => User::class,
        ]);
        $item2 = OrderItemFactory::new()->make([
            'owner_id' => 2,
            'owner_type' => User::class,
        ]);
        $item3 = OrderItemFactory::new()->make([
            'owner_id' => 2,
            'owner_type' => User::class,
        ]);

        $collection = new OrderItemCollection([$item1, $item2, $item3]);

        $owner_1 = new User;
        $owner_1->id = 1;

        $owner_2 = new User;
        $owner_2->id = 2;
        $owner_1_group = $collection->whereOwner($owner_1);
        $owner_2_group = $collection->whereOwner($owner_2);

        $this->assertInstanceOf(OrderItemCollection::class, $owner_1_group);
        $this->assertEquals(1, $owner_1_group->count());
        $this->assertEquals($item1, $owner_1_group->get(0));
        $this->assertTrue($owner_1_group->contains($item1));

        $this->assertInstanceOf(OrderItemCollection::class, $owner_2_group);
        $this->assertEquals(2, $owner_2_group->count());
        $this->assertTrue($owner_2_group->contains($item2));
        $this->assertTrue($owner_2_group->contains($item3));
    }

    /** @test */
    public function testWhereCurrency()
    {
        $item1 = OrderItemFactory::new()->EUR()->make();
        $item2 = OrderItemFactory::new()->USD()->make();
        $item3 = OrderItemFactory::new()->USD()->make();

        $collection = new OrderItemCollection([$item1, $item2, $item3]);

        $eur_group = $collection->whereCurrency('EUR');
        $usd_group = $collection->whereCurrency('USD');

        $this->assertInstanceOf(OrderItemCollection::class, $eur_group);
        $this->assertEquals(1, $eur_group->count());
        $this->assertEquals($item1, $eur_group->get(0));
        $this->assertTrue($eur_group->contains($item1));

        $this->assertInstanceOf(OrderItemCollection::class, $usd_group);
        $this->assertEquals(2, $usd_group->count());
        $this->assertTrue($usd_group->contains($item2));
        $this->assertTrue($usd_group->contains($item3));
    }

    /** @test */
    public function testChunkByOwner()
    {
        User::factory()->create(['id' => 1]);
        User::factory()->create(['id' => 2]);

        $collection = new OrderItemCollection([
            OrderItemFactory::new()->make([
                'owner_id' => 1,
                'owner_type' => User::class,
            ]),
            OrderItemFactory::new()->make([
                'owner_id' => 2,
                'owner_type' => User::class,
            ]),
            OrderItemFactory::new()->make([
                'owner_id' => 2,
                'owner_type' => User::class,
            ]),
        ]);

        $result = $collection->chunkByOwner();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(2, $result->count());

        $this->assertEquals([
            'Laravel\Cashier\Tests\Fixtures\User_1',
            'Laravel\Cashier\Tests\Fixtures\User_2',
        ], $result->keys()->all());

        $result->flatten()->each(function ($item) {
            $this->assertInstanceOf(Cashier::$orderItemModel, $item);
        });
    }

    /** @test */
    public function testChunkByCurrency()
    {
        $collection = new OrderItemCollection([
            OrderItemFactory::new()->USD()->make(),
            OrderItemFactory::new()->USD()->make(),
            OrderItemFactory::new()->EUR()->make(),
        ]);

        $result = $collection->chunkByCurrency();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(2, $result->count());

        $this->assertEquals(['EUR', 'USD'], $result->keys()->all());
    }

    /** @test */
    public function testChunkByOwnerAndCurrency()
    {
        User::factory()->create(['id' => 1]);
        User::factory()->create(['id' => 2]);

        $collection = new OrderItemCollection([
            OrderItemFactory::new()->USD()->make([
                'owner_id' => 1,
                'owner_type' => User::class,
            ]),
            OrderItemFactory::new()->USD()->make([
                'owner_id' => 2,
                'owner_type' => User::class,
            ]),
            OrderItemFactory::new()->EUR()->make([
                'owner_id' => 2,
                'owner_type' => User::class,
            ]),
        ]);

        $result = $collection->chunkByOwnerAndCurrency();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(3, $result->count());

        $this->assertEquals([
            'Laravel\Cashier\Tests\Fixtures\User_1_USD',
            'Laravel\Cashier\Tests\Fixtures\User_2_EUR',
            'Laravel\Cashier\Tests\Fixtures\User_2_USD',
        ], $result->keys()->all());
    }

    /** @test */
    public function testTaxPercentages()
    {
        $collection = new OrderItemCollection([
            OrderItemFactory::new()->make(['tax_percentage' => 21.5]),
            OrderItemFactory::new()->make(['tax_percentage' => 21.5]),
            OrderItemFactory::new()->make(['tax_percentage' => 6]),
        ]);

        $this->assertEquals(['6', '21.5'], $collection->taxPercentages()->all());
    }
}
