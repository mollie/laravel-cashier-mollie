<?php

namespace Laravel\Cashier\Tests\Charge;

use Laravel\Cashier\Http\RedirectToCheckoutResponse;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Fixtures\User;
use Money\Currency;
use Money\Money;

class FirstPaymentChargeBuilderTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withPackageMigrations();
        $this->withMockedCreateMollieCustomer();
    }

    /** @test */
    public function redirectToCheckoutResponse()
    {
        $this->withMockedCreateMolliePayment(1, '3.00');
        $owner = User::factory()->create();
        $this->assertEquals(0, $owner->orderItems()->count());
        $this->assertEquals(0, $owner->orders()->count());

        $item = new \Laravel\Cashier\Charge\ChargeItemBuilder($owner);
        $item->unitPrice(new Money(100, new Currency('EUR')));
        $item->description('Test Item');
        $chargeItem = $item->make();

        $item2 = new \Laravel\Cashier\Charge\ChargeItemBuilder($owner);
        $item2->unitPrice(new Money(200, new Currency('EUR')));
        $item2->description('Test Item 2');
        $chargeItem2 = $item2->make();

        $builder = $owner->newCharge()
            ->addItem($chargeItem)
            ->addItem($chargeItem2)
            ->create();

        $this->assertEquals(0, $owner->orderItems()->count());
        $this->assertEquals(0, $owner->orders()->count());
        $this->assertInstanceOf(RedirectToCheckoutResponse::class, $builder);
    }
}
