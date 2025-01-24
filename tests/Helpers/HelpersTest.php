<?php

namespace Laravel\Cashier\Tests\Helpers;

use Money\Money;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function test_money()
    {
        $money = money(1234, 'EUR');

        $this->assertInstanceOf(Money::class, $money);
        $this->assertTrue(Money::EUR(1234)->equals($money));
    }

    /** @test */
    public function test_decimal_to_money()
    {
        $money = decimal_to_money('12.34', 'EUR');
        $this->assertTrue(Money::EUR(1234)->equals($money));
    }

    /** @test */
    public function test_money_to_mollie_array()
    {
        $moneyEUR = Money::EUR(1234);
        $moneyUSD = Money::USD(9876);

        $arrayEUR = money_to_mollie_array($moneyEUR);
        $arrayUSD = money_to_mollie_array($moneyUSD);

        $this->assertEquals([
            'currency' => 'EUR',
            'value' => '12.34',
        ], $arrayEUR);

        $this->assertEquals([
            'currency' => 'USD',
            'value' => '98.76',
        ], $arrayUSD);
    }

    /** @test */
    public function test_mollie_array_to_money()
    {
        $arrayEUR = [
            'currency' => 'EUR',
            'value' => '12.34',
        ];

        $arrayUSD = [
            'currency' => 'USD',
            'value' => '98.76',
        ];

        $moneyEUR = mollie_array_to_money($arrayEUR);
        $moneyUSD = mollie_array_to_money($arrayUSD);

        $this->assertTrue(Money::EUR(1234)->equals($moneyEUR));
        $this->assertTrue(Money::USD(9876)->equals($moneyUSD));
    }

    /** @test */
    public function test_mollie_object_to_money()
    {
        $objectEUR = (object) [
            'currency' => 'EUR',
            'value' => '12.34',
        ];

        $objectUSD = (object) [
            'currency' => 'USD',
            'value' => '98.76',
        ];

        $moneyEUR = mollie_object_to_money($objectEUR);
        $moneyUSD = mollie_object_to_money($objectUSD);

        $this->assertTrue(Money::EUR(1234)->equals($moneyEUR));
        $this->assertTrue(Money::USD(9876)->equals($moneyUSD));
    }
}
