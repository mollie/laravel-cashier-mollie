<?php

namespace Laravel\Cashier\Tests\Credit;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Fixtures\User;
use Money\Money;

class CreditTest extends BaseTestCase
{
    /** @test */
    public function testAddAmountForOwner()
    {
        $this->withPackageMigrations();
        $user = factory(User::class)->create();

        Cashier::$creditModel::addAmountForOwner($user, Money::EUR(12345));
        Cashier::$creditModel::addAmountForOwner($user, Money::EUR(12346));
        Cashier::$creditModel::addAmountForOwner($user, Money::USD(12348));

        $creditEUR = $user->credit('EUR');
        $this->assertEquals(24691, $creditEUR->value);
        $this->assertEquals('EUR', $creditEUR->currency);
        $this->assertTrue(Money::EUR(24691)->equals($creditEUR->money()));

        $creditUSD = $user->credit('USD');
        $this->assertEquals(12348, $creditUSD->value);
        $this->assertEquals('USD', $creditUSD->currency);
        $this->assertTrue(Money::USD(12348)->equals($creditUSD->money()));
    }

    /** @test */
    public function testMaxOutForOwner()
    {
        $this->withPackageMigrations();
        $user = factory(User::class)->create();

        Cashier::$creditModel::addAmountForOwner($user, Money::USD(12348));
        $usedUSD = Cashier::$creditModel::maxOutForOwner($user, Money::USD(20025));

        $this->assertEquals(Money::USD(12348), $usedUSD);
        $this->assertEquals(0, Cashier::$creditModel::whereOwner($user)->whereCurrency('USD')->first()->value);

        Cashier::$creditModel::addAmountForOwner($user, Money::EUR(12346));
        $usedEUR = Cashier::$creditModel::maxOutForOwner($user, Money::EUR(510));

        $this->assertTrue(Money::EUR(510)->equals($usedEUR));
        $this->assertEquals(11836, Cashier::$creditModel::whereOwner($user)->whereCurrency('EUR')->first()->value);
    }
}
