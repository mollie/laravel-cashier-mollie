<?php

namespace Laravel\Cashier\Tests\Order;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Order\OrderNumberGenerator;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderFactory;
use PHPUnit\Framework\Attributes\Test;

class OrderNumberGeneratorTest extends BaseTestCase
{
    private $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new OrderNumberGenerator;
    }

    #[Test]
    public function canGenerateANumber()
    {
        $this->assertNotNull($this->generator->generate());
    }

    #[Test]
    public function numberStartsWithCurrentYear()
    {
        Carbon::setTestNow(Carbon::parse('1 jul 2018'));

        $this->assertTrue(Str::startsWith($number = $this->generator->generate(), '2018-'));
    }

    #[Test]
    public function usesConfiguredOffsetAndModelCount()
    {
        config(['cashier.order_number_generator.offset' => 15]);
        $this->generator = new OrderNumberGenerator;

        $this->assertTrue(Str::endsWith($this->generator->generate(), '16'));

        OrderFactory::new()->times(3)->create();
        $this->assertTrue(Str::endsWith($this->generator->generate(), '19'));
    }

    #[Test]
    public function hasAReadableFormat()
    {
        Carbon::setTestNow(Carbon::parse(('1 jul 2018')));
        config(['cashier.order_number_generator.offset' => 123455]);
        $this->generator = new OrderNumberGenerator;

        $this->assertEquals('2018-0012-3456', $this->generator->generate());
    }
}
