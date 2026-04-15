<?php

namespace Laravel\Cashier\Tests\FirstPayment\Actions;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\Coupon\Contracts\CouponRepository;
use Laravel\Cashier\FirstPayment\Actions\ApplySubscriptionCouponToPayment as Action;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;
use Laravel\Cashier\Tests\Fixtures\User;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function testGetTotalReturnsDiscountSubtotal()
    {
        $this->assertMoneyEURCents(-500, $this->action->getTotal());
    }

    #[Test]
    public function testTaxDefaultsToZero()
    {
        $this->assertEquals(0, $this->action->getTaxPercentage());
        $this->assertMoneyEURCents(0, $this->action->getTax());
    }

    #[Test]
    public function testCreateFromPayloadReturnsNull()
    {
        $this->assertNull(Action::createFromPayload(['foo' => 'bar'], User::factory()->make()));
    }

    #[Test]
    public function testGetPayloadReturnsNull()
    {
        $this->assertNull($this->action->getPayload());
    }

    #[Test]
    public function testExecuteReturnsEmptyOrderItemCollection()
    {
        $result = $this->action->execute();
        $this->assertEquals(new OrderItemCollection, $result);
    }
}
