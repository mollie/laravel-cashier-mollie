<?php

namespace Laravel\Cashier\Tests\Coupon;

use Laravel\Cashier\Coupon\ConfigCouponRepository;
use Laravel\Cashier\Coupon\Contracts\CouponRepository;
use Laravel\Cashier\Coupon\Coupon;
use Laravel\Cashier\Exceptions\CouponNotFoundException;
use Laravel\Cashier\Tests\BaseTestCase;

class ConfigCouponRepositoryTest extends BaseTestCase
{
    /** @var ConfigCouponRepository */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $defaults = [
            'handler' => '\NonExistentHandler',
            'times' => 6,
            'context' => [
                'foo' => 'bar',
            ],
        ];
        $coupons = [
            'test-coupon' => [
                'handler' => \Laravel\Cashier\Coupon\FixedDiscountHandler::class,
                'context' => [
                    'description' => 'Welcome to '.config('app.name'),
                    'discount' => [
                        'currency' => 'EUR',
                        'value' => '5.00',
                    ],
                ],
            ],
        ];
        $this->repository = new ConfigCouponRepository($defaults, $coupons);
    }

    /** @test */
    public function it_is_container_bound()
    {
        $repository = app()->make(CouponRepository::class);
        $this->assertInstanceOf(ConfigCouponRepository::class, $repository);
    }

    /** @test */
    public function find_returns_null_when_not_found()
    {
        $this->assertNull($this->repository->find('some_wrong_name'));
    }

    /** @test */
    public function find_returns_coupon_when_found()
    {
        $this->assertInstanceOf(Coupon::class, $this->repository->find('test-coupon'));
    }

    /** @test */
    public function find_or_fail_correct()
    {
        $this->assertInstanceOf(Coupon::class, $this->repository->findOrFail('test-coupon'));
    }

    /** @test */
    public function find_or_fail_wrong()
    {
        $this->expectException(CouponNotFoundException::class);
        $this->repository->findOrFail('some_wrong_name');
    }

    /** @test */
    public function find_or_fail_is_case_insensitive()
    {
        $lowercaseCoupon = $this->repository->find('test-coupon');
        $uppercaseCoupon = $this->repository->find('TEST-COUPON');
        $this->assertInstanceOf(Coupon::class, $lowercaseCoupon);
        $this->assertInstanceOf(Coupon::class, $uppercaseCoupon);
        $this->assertEquals($lowercaseCoupon, $uppercaseCoupon);
    }

    /** @test */
    public function it_handles_times_attribute()
    {
        $coupon = $this->repository->findOrFail('test-coupon');

        $this->assertEquals(6, $coupon->times());
    }
}
