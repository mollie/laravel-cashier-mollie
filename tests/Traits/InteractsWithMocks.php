<?php

namespace Laravel\Cashier\Tests\Traits;

use Illuminate\Support\Arr;
use Laravel\Cashier\Coupon\Contracts\CouponRepository;
use Laravel\Cashier\Coupon\Coupon;
use Laravel\Cashier\Coupon\FixedDiscountHandler;
use Laravel\Cashier\Mollie\Contracts\CreateMollieCustomer;
use Laravel\Cashier\Mollie\Contracts\CreateMolliePayment;
use Laravel\Cashier\Mollie\Contracts\GetMollieCustomer;
use Laravel\Cashier\Mollie\Contracts\GetMollieMandate;
use Laravel\Cashier\Mollie\Contracts\GetMollieMethodMinimumAmount;
use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;
use Laravel\Cashier\Mollie\Contracts\UpdateMolliePayment;
use Laravel\Cashier\Mollie\GetMollieMethodMaximumAmount;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\Customer;
use Mollie\Api\Resources\Mandate;
use Mollie\Api\Resources\Payment;
use Money\Currency;
use Money\Money;
use Mollie\Api\MollieApiClient;

/**
 * @mixin \Laravel\Cashier\Tests\BaseTestCase
 */
trait InteractsWithMocks
{
    public function setUpInteractsWithMocks(): void
    {
        if (!$this->interactWithMollieAPI) {
            // Disable the Mollie API
            $this->mock(MollieApiClient::class, null);
        }
    }

    /**
     * @param  \Laravel\Cashier\Coupon\Coupon  $coupon
     * @param  null  $couponHandler
     * @param  null  $context
     * @return CouponRepository The mocked coupon repository
     */
    protected function withMockedCouponRepository(Coupon $coupon = null, $couponHandler = null, $context = null)
    {
        if (is_null($couponHandler)) {
            $couponHandler = new FixedDiscountHandler;
        }

        if (is_null($context)) {
            $context = [
                'description' => 'Test coupon',
                'discount' => [
                    'value' => '5.00',
                    'currency' => 'EUR',
                ],
            ];
        }

        if (is_null($coupon)) {
            $coupon = new Coupon(
                'test-coupon',
                $couponHandler,
                $context
            );
        }

        return $this->mock(CouponRepository::class, function ($mock) use ($coupon) {
            return $mock->shouldReceive('findOrFail')->with($coupon->name())->andReturn($coupon);
        });
    }

    /**
     * @param  \Laravel\Cashier\Coupon\Coupon  $coupon
     * @param  null  $couponHandler
     * @param  null  $context
     * @return CouponRepository The mocked coupon repository
     */
    protected function withMockedUsdCouponRepository(Coupon $coupon = null, $couponHandler = null, $context = null)
    {
        if (is_null($couponHandler)) {
            $couponHandler = new FixedDiscountHandler;
        }

        if (is_null($context)) {
            $context = [
                'description' => 'Test USD coupon',
                'discount' => [
                    'value' => '5.00',
                    'currency' => 'USD',
                ],
            ];
        }

        if (is_null($coupon)) {
            $coupon = new Coupon(
                'usddiscount',
                $couponHandler,
                $context
            );
        }

        return $this->mock(CouponRepository::class, function ($mock) use ($coupon) {
            return $mock->shouldReceive('findOrFail')->with($coupon->name())->andReturn($coupon);
        });
    }

    protected function withMockedGetMollieMandateAccepted(int $times = 1, array $attributes = []): void
    {
        $attributes = $this->wrapIfNotNested($attributes);

        $this->withMockedGetMollieMandate($times, array_map(fn ($data) => array_merge(
            [
                'status' => 'valid',
                'mandateId' => 'mdt_unique_mandate_id',
                'customerId' => 'cst_unique_customer_id',
            ],
            $data
        ), $attributes));
    }

    protected function withMockedGetMollieMandateRevoked(int $times = 1, array $attributes = []): void
    {
        $attributes = $this->wrapIfNotNested($attributes);

        $this->withMockedGetMollieMandate(
            $times,
            array_map(fn ($data) => array_merge(
                [
                    'status' => 'revoked',
                    'mandateId' => 'mdt_unique_mandate_id',
                    'customerId' => 'cst_unique_customer_id',
                ],
                $data
            ), $attributes)
        );
    }

    protected function withMockedGetMollieMandatePending(int $times = 1): void
    {
        $this->withMockedGetMollieMandate($times, [
            [
                'mandateId' => 'mdt_unique_mandate_id',
                'customerId' => 'cst_unique_customer_id',
                'status' => 'pending',
            ]
        ], null);
    }

    protected function withMockedGetMollieMandate(
        int $times = 1,
        array $attributes = [
            [
                'mandateId' => 'mdt_unique_mandate_id',
                'customerId' => 'cst_unique_customer_id',
                'status' => 'valid',
            ]
        ]
    ): void {
        $this->mock(GetMollieMandate::class, function ($mock) use ($times, $attributes) {
            foreach ($attributes as $data) {
                $mandate = new Mandate(resolve(MollieApiClient::class));
                $mandate->id = $data['mandateId'];
                $mandate->status = $data['status'];
                $mandate->method = 'directdebit';

                $mock->shouldReceive('execute')
                    ->with($data['customerId'], $data['mandateId'], null)
                    ->times($times)
                    ->andReturn($mandate);
            }

            return $mock;
        });
    }

    protected function withMockedCreateMollieCustomer(int $times = 1)
    {
        $this->mock(CreateMollieCustomer::class, function ($mock) use ($times) {
            $customer = new Customer(resolve(MollieApiClient::class));
            $customer->id = 'cst_unique_customer_id';

            return $mock->shouldReceive('execute')
                ->times($times)
                ->andReturn($customer);
        });
    }

    protected function withMockedGetMollieCustomer(int $times = 1, array $customerIds = ['cst_unique_customer_id']): void
    {
        $this->mock(GetMollieCustomer::class, function ($mock) use ($customerIds, $times) {
            foreach ($customerIds as $id) {
                $customer = new Customer(resolve(MollieApiClient::class));
                $customer->id = $id;

                $mock->shouldReceive('execute')
                    ->with($id, null)
                    ->times($times)
                    ->andReturn($customer);
            }

            return $mock;
        });
    }

    protected function withMockedGetMollieMethodMinimumAmount(int $times = 1, int $amount = 1): void
    {
        $this->mock(GetMollieMethodMinimumAmount::class, function ($mock) use ($times, $amount) {
            return $mock->shouldReceive('execute')
                ->with('directdebit', 'EUR', null)
                ->times($times)
                ->andReturn(new Money($amount, new Currency('EUR')));
        });
    }

    protected function withMockedGetMollieMethodMaximumAmount(int $times = 1, ?int $amount = 30000): void
    {
        $returnedValue = $amount === null ? null : new Money($amount, new Currency('EUR'));

        $this->mock(GetMollieMethodMaximumAmount::class, function ($mock) use ($times, $returnedValue) {
            return $mock->shouldReceive('execute')
                ->with('directdebit', 'EUR', null)
                ->times($times)
                ->andReturn($returnedValue);
        });
    }

    protected function withMockedCreateMolliePayment(int $times = 1, string $amount = '10.00', string $paymentId = 'tr_unique_payment_id')
    {
        $this->mock(CreateMolliePayment::class, function ($mock) use ($times, $amount, $paymentId) {
            $payment = new Payment(resolve(MollieApiClient::class));
            $payment->id = $paymentId;
            $payment->amount = (object) [
                'currency' => 'EUR',
                'value' => $amount,
            ];
            $payment->_links = json_decode(json_encode([
                'checkout' => [
                    'href' => 'https://foo-redirect-bar.com',
                    'type' => 'text/html',
                ],
            ]));
            $payment->mandateId = 'mdt_dummy_mandate_id';

            return $mock->shouldReceive('execute')
                ->times($times)
                ->andReturn($payment);
        });
    }

    protected function withMockedUpdateMolliePayment(
        int $times = 1,
        string $amount = '10.00',
        string $redirectUrl = 'https://foo-redirect-bar.com',
        string $paymentId = 'tr_unique_payment_id',
    ) {
        $this->mock(UpdateMolliePayment::class, function ($mock) use ($times, $amount, $paymentId, $redirectUrl) {
            $payment = new Payment(resolve(MollieApiClient::class));
            $payment->id = $paymentId;
            $payment->amount = (object) [
                'currency' => 'EUR',
                'value' => $amount,
            ];
            $payment->redirectUrl = $redirectUrl;
            $payment->mandateId = 'mdt_dummy_mandate_id';

            return $mock->shouldReceive('execute')
                ->times($times)
                ->andReturn($payment);
        });
    }

    protected function withMockedGetMolliePayment(int $times = 1, ?Payment $molliePayment = null)
    {
        if (is_null($molliePayment)) {
            $molliePayment = new Payment(resolve(MollieApiClient::class));
            $molliePayment->id = 'tr_unique_payment_id';
        }

        $this->mock(GetMolliePayment::class, function ($mock) use ($times, $molliePayment) {
            return $mock->shouldReceive('execute')
                ->with($molliePayment->id, [], null)
                ->times($times)
                ->andReturn($molliePayment);
        });
    }

    protected function withMockedGetMolliePaymentThrowingException(int $times = 1, string $wrongId = 'tr_wrong_payment_id')
    {
        $this->mock(GetMolliePayment::class, function ($mock) use ($times, $wrongId) {
            return $mock->shouldReceive('execute')
                ->with($wrongId, [], null)
                ->times($times)
                ->andThrow(new ApiException);
        });
    }

    private function wrapIfNotNested(array $attributes = []): array
    {
        // check if the provided attributes are at least two levels deep/nested
        if (strpos(array_key_first(Arr::dot($attributes)), '.') === false) {
            return [$attributes];
        }

        return $attributes;
    }
}
