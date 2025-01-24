<?php

namespace Laravel\Cashier\Tests\SubscriptionBuilder;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\FirstPaymentPaid;
use Laravel\Cashier\Events\OrderProcessed;
use Laravel\Cashier\Events\SubscriptionStarted;
use Laravel\Cashier\Exceptions\CouponException;
use Laravel\Cashier\FirstPayment\Actions\AddGenericOrderItem;
use Laravel\Cashier\FirstPayment\Actions\StartSubscription;
use Laravel\Cashier\Order\Order;
use Laravel\Cashier\SubscriptionBuilder\FirstPaymentSubscriptionBuilder;
use Laravel\Cashier\SubscriptionBuilder\RedirectToCheckoutResponse;
use Laravel\Cashier\Tests\BaseTestCase;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Payment as MolliePayment;

class FirstPaymentSubscriptionBuilderTest extends BaseTestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        Cashier::useCurrency('eur');
        $this->withTestNow('2019-01-01');
        $this->withConfiguredPlans();
        $this->user = $this->getCustomerUser(true, [
            'tax_percentage' => 20,
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);
    }

    /** @test */
    public function creates_a_first_payment_for_subscription()
    {
        $firstPayment = config('cashier_plans.defaults.first_payment');
        $firstPayment['redirect_url'] = 'https://foo-redirect-bar.com';
        $firstPayment['webhook_url'] = 'https://foo-webhook-bar.com';
        config(['cashier_plans.plans.monthly-10-1.first_payment' => $firstPayment]);
        config(['cashier.locale' => 'nl_NL']);

        $this->withMockedGetMollieCustomer(2);
        $this->withMockedCreateMolliePayment();

        $builder = $this->getBuilder()
            ->nextPaymentAt(now()->addDays(12))
            ->trialDays(5);

        $response = $builder->create();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertInstanceOf(RedirectToCheckoutResponse::class, $response);
        $this->assertInstanceOf(MolliePayment::class, $response->payment());

        $payload = $builder->getMandatePaymentBuilder()->getMolliePayload();

        $this->assertEquals([
            'sequenceType' => 'first',
            'method' => ['ideal'],
            'customerId' => $this->user->mollie_customer_id,
            'description' => 'Test mandate payment',
            'amount' => [
                'value' => '0.05',
                'currency' => 'EUR',
            ],
            'webhookUrl' => 'https://foo-webhook-bar.com',
            'redirectUrl' => 'https://foo-redirect-bar.com',
            'locale' => 'nl_NL',
            'metadata' => [
                'owner' => [
                    'type' => get_class($this->user),
                    'id' => 1,
                ],
            ],
        ], $payload);
        $localPayment = Cashier::$paymentModel::find(1);
        $this->assertEquals('Monthly payment', $localPayment->first_payment_actions[0]->description);
        $this->assertEquals('Laravel\\Cashier\\FirstPayment\\Actions\\StartSubscription', $localPayment->first_payment_actions[0]->handler);
        $this->assertEquals('EUR', $localPayment->first_payment_actions[0]->subtotal->currency);
        $this->assertEquals(0, $localPayment->first_payment_actions[0]->subtotal->value);
        $this->assertEquals(20, $localPayment->first_payment_actions[0]->taxPercentage);
        $this->assertEquals('monthly-10-1', $localPayment->first_payment_actions[0]->plan);
        $this->assertEquals('default', $localPayment->first_payment_actions[0]->name);
        $this->assertEquals(1, $localPayment->first_payment_actions[0]->quantity);
        $this->assertEquals(now()->addDays(12)->toIso8601String(), $localPayment->first_payment_actions[0]->nextPaymentAt);
        $this->assertEquals(now()->addDays(5)->toIso8601String(), $localPayment->first_payment_actions[0]->trialUntil);

        $this->assertEquals('Test mandate payment', $localPayment->first_payment_actions[1]->description);
        $this->assertEquals('Laravel\\Cashier\\FirstPayment\\Actions\\AddGenericOrderItem', $localPayment->first_payment_actions[1]->handler);
        $this->assertEquals('EUR', $localPayment->first_payment_actions[1]->unit_price->currency);
        $this->assertEquals(0.04, $localPayment->first_payment_actions[1]->unit_price->value);
        $this->assertEquals(20, $localPayment->first_payment_actions[1]->taxPercentage);
    }

    /** @test */
    public function handles_quantity()
    {
        config(['cashier.locale' => 'nl_NL']);

        $this->withMockedGetMollieCustomer(2);
        $this->withMockedCreateMolliePayment();

        $builder = $this->getBuilder()->quantity(3);

        $response = $builder->create();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertInstanceOf(RedirectToCheckoutResponse::class, $response);
        $this->assertInstanceOf(MolliePayment::class, $response->payment());

        $payload = $builder->getMandatePaymentBuilder()->getMolliePayload();

        $localPayment = Cashier::$paymentModel::find(1);
        $this->assertEquals(3, $localPayment->first_payment_actions[0]->quantity);

        $this->assertEquals([
            'currency' => 'EUR',
            'value' => 36,
        ], $payload['amount']);
    }

    /** @test */
    public function handles_a_paid_first_payment()
    {
        $this->withoutExceptionHandling();

        Event::fake();

        $molliePayment = new MolliePayment(new MollieApiClient);
        $molliePayment->id = 'tr_unique_payment_id';
        $molliePayment->paidAt = Carbon::now()->toIso8601String();
        $molliePayment->mandateId = 'mdt_unique_mandate_id';
        $molliePayment->amount = (object) [
            'currency' => 'EUR',
            'value' => '0.05',
        ];
        $molliePayment->metadata = json_decode(json_encode([
            'owner' => [
                'type' => get_class($this->user),
                'id' => 1,
            ],
            'actions' => [
                [
                    'handler' => StartSubscription::class,
                    'description' => 'Monthly payment',
                    'subtotal' => [
                        'value' => '0.00',
                        'currency' => 'EUR',
                    ],
                    'taxPercentage' => 20,
                    'plan' => 'monthly-10-1',
                    'name' => 'default',
                    'quantity' => 1,
                    'nextPaymentAt' => now()->addDays(12)->toIso8601String(),
                    'trialUntil' => now()->addDays(5)->toIso8601String(),
                ],
                [
                    'handler' => AddGenericOrderItem::class,
                    'description' => 'Test mandate payment',
                    'subtotal' => [
                        'value' => '0.04',
                        'currency' => 'EUR',
                    ],
                    'taxPercentage' => 20,
                ],
            ],
        ]));

        Cashier::$paymentModel::createFromMolliePayment($molliePayment, $this->user);

        $this->withMockedGetMolliePayment(1, $molliePayment);
        $this->withMockedGetMollieMandateAccepted(2);

        $this->withMockedGetMollieCustomer(2);

        $this->assertFalse($this->user->subscribed());
        $this->assertNull($this->user->mollie_mandate_id);

        $this->withMockedUpdateMolliePayment();

        $response = $this->post(route('webhooks.mollie.first_payment', [
            'id' => 'tr_unique_payment_id',
        ]));

        $response->assertStatus(200);

        $this->user = $this->user->fresh();
        $this->assertTrue($this->user->subscribed());
        $this->assertTrue($this->user->onTrial());
        $this->assertNotNull($this->user->mollie_mandate_id);

        Event::assertDispatched(OrderProcessed::class);
        Event::assertDispatched(FirstPaymentPaid::class);

        $subscription = $this->user->subscription('default')->fresh();

        Event::assertDispatched(SubscriptionStarted::class, function (SubscriptionStarted $e) use ($subscription) {
            $this->assertTrue($e->subscription->is($subscription));

            return true;
        });
        $this->assertSame(Order::first()->items->first()->description_extra_lines[0], 'From 2019-01-01 to 2019-02-01');
    }

    /** @test */
    public function test_with_coupon_no_trial_validates_coupon()
    {
        $this->expectException(CouponException::class);
        $this->withMockedCouponRepository(null, new InvalidatingCouponHandler);
        $this->getBuilder()->withCoupon('test-coupon')->create();
    }

    /** @test */
    public function test_with_coupon_with_trial_validates_coupon()
    {
        $this->expectException(CouponException::class);
        $this->withMockedCouponRepository(null, new InvalidatingCouponHandler);
        $this->getBuilder()->trialDays(5)->withCoupon('test-coupon')->create();
    }

    /** @test */
    public function test_with_coupon_no_trial_modifies_the_payment_amount()
    {
        $this->withMockedCouponRepository();
        $this->withMockedCreateMolliePayment();
        $this->withMockedGetMollieCustomer(2);

        $builder = $this->getBuilder()->withCoupon('test-coupon');
        $builder->create();

        $amount = $builder->getMandatePaymentBuilder()->getMolliePayload()['amount'];

        $this->assertEquals('7.00', $amount['value']);
        $this->assertEquals('EUR', $amount['currency']);
    }

    /** @test */
    public function test_handles_trial_days()
    {
        $this->withMockedCreateMolliePayment();
        $this->withMockedGetMollieCustomer(2);
        $trialBuilder = $this->getBuilder();

        $trialBuilder->trialDays(5)->create();

        $this->assertEquals(
            '0.05',
            $trialBuilder->getMandatePaymentBuilder()->getMolliePayload()['amount']['value']
        );
    }

    /** @test */
    public function test_handles_no_trial_mode()
    {
        $this->withMockedCreateMolliePayment();
        $this->withMockedGetMollieCustomer(2);
        $skipTrialBuilder = $this->getBuilder()->trialDays(5)->skipTrial();

        $skipTrialBuilder->create();

        $this->assertEquals(
            '12.00',
            $skipTrialBuilder->getMandatePaymentBuilder()->getMolliePayload()['amount']['value']
        );
    }

    /**
     * @return \Laravel\Cashier\SubscriptionBuilder\FirstPaymentSubscriptionBuilder
     */
    protected function getBuilder()
    {
        return new FirstPaymentSubscriptionBuilder(
            $this->user,
            'default',
            'monthly-10-1'
        );
    }
}
