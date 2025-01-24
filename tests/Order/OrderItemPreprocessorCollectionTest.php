<?php

namespace Laravel\Cashier\Tests\Order;

use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Order\OrderItemPreprocessorCollection;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;

class OrderItemPreprocessorCollectionTest extends BaseTestCase
{
    /** @test */
    public function handles_order_item()
    {
        $fakePreprocessor = $this->getFakePreprocessor(OrderItemFactory::new()->times(2)->make());
        $preprocessors = new OrderItemPreprocessorCollection([$fakePreprocessor]);
        $item = OrderItemFactory::new()->make();

        $result = $preprocessors->handle($item);

        $this->assertInstanceOf(OrderItemCollection::class, $result);
        $this->assertEquals(2, $result->count());
        $fakePreprocessor->assertOrderItemHandled($item);
    }

    /** @test */
    public function invokes_preprocessors_one_by_one()
    {
        $preprocessor1 = $this->getFakePreprocessor(OrderItemFactory::new()->times(1)->make());
        $preprocessor2 = $this->getFakePreprocessor(OrderItemFactory::new()->times(2)->make());
        $preprocessors = new OrderItemPreprocessorCollection([$preprocessor1, $preprocessor2]);
        $item = OrderItemFactory::new()->make();

        $result = $preprocessors->handle($item);

        $this->assertInstanceOf(OrderItemCollection::class, $result);
        $this->assertEquals(2, $result->count());
    }

    /** @test */
    public function handles_empty_preprocessor_collection()
    {
        $preprocessors = new OrderItemPreprocessorCollection;
        $item = OrderItemFactory::new()->make();

        $result = $preprocessors->handle($item);

        $this->assertInstanceOf(OrderItemCollection::class, $result);
        $this->assertEquals(1, $result->count());
        $this->assertTrue($result->first()->is($item));
    }

    /**
     * @return \Laravel\Cashier\Tests\Order\FakeOrderItemPreprocessor
     */
    protected function getFakePreprocessor(OrderItemCollection $items)
    {
        return (new FakeOrderItemPreprocessor)->withResult($items);
    }
}
