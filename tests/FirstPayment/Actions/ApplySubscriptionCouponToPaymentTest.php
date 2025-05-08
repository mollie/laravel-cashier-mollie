<?php

namespace Laravel\Cashier\Tests\FirstPayment\Actions;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\Coupon\Contracts\CouponRepository;
use Laravel\Cashier\FirstPayment\Actions\ApplySubscriptionCouponToPayment as Action;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;
use Laravel\Cashier\Tests\Fixtures\User;

class ApplySubscriptionCouponToPaymentTest extends BaseTestCase
{
    private $action;

    private $coupon;

    private $owner;

    protected function setUp(): void
    {
        parent::setUp();

        Cashier::useCurrency('eur');

        $this->withMockedCouponRepository();
        $this->coupon = app()->make(CouponRepository::class)->findOrFail('test-coupon');
        $this->owner = User::factory()->make();
        $orderItems = OrderItemFactory::new()->make([
            'unit_price' => 10000,
            'currency' => 'EUR',
        ])->toCollection();

        $this->action = new Action($this->owner, $this->coupon, $orderItems);
    }

    /** @test */
    public function test_get_total_returns_discount_subtotal()
    {
        $this->assertMoneyEURCents(-500, $this->action->getTotal());
    }

    /** @test */
    public function test_tax_defaults_to_zero()
    {
        $this->assertEquals(0, $this->action->getTaxPercentage());
        $this->assertMoneyEURCents(0, $this->action->getTax());
    }

    /** @test */
    public function test_create_from_payload_returns_null()
    {
        $this->assertNull(Action::createFromPayload(['foo' => 'bar'], User::factory()->make()));
    }

    /** @test */
    public function test_get_payload_returns_null()
    {
        $this->assertNull($this->action->getPayload());
    }

    /** @test */
    public function test_execute_returns_empty_order_item_collection()
    {
        $result = $this->action->execute();
        $this->assertEquals(new OrderItemCollection, $result);
    }
}
