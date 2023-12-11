<?php

namespace Laravel\Cashier\Tests\SubscriptionBuilder;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\SubscriptionBuilder\FirstPaymentSubscriptionBuilder;
use Laravel\Cashier\Tests\BaseTestCase;
use Money\Money;

class FirstPaymentSubscriptionBuilderApplyCorrectTaxTest extends BaseTestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        Cashier::useCurrency('eur');
        $this->withTestNow('2019-01-01');
        $this->withPackageMigrations();
        $this->withConfiguredPlans();
        $this->user = $this->getCustomerUser(true, [
            'tax_percentage' => 21,
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);
    }

    /** @test */
    public function handlesTrialDaysAndFirstPaymentWithTaxAppliedCorrectly()
    {
        $firstPaymentAmounts = collect(['10.00', '11.00', '21.00', '24.00', '280.00']);

        $firstPaymentAmounts->each(function ($amount) {
            $this->withMockedCreateMolliePayment();
            $this->withMockedGetMollieCustomer(2);

            config(['cashier_plans.defaults.first_payment.amount.value' => $amount]);

            $trialBuilder = $this->getBuilder();
            $trialBuilder->trialDays(5)->create();
            $this->assertEquals(
                $amount,
                $trialBuilder->getMandatePaymentBuilder()->getMolliePayload()['amount']['value']
            );
        });
    }

    /** @test */
    public function roundingModeReturnCorrectValue()
    {
        $down = $this->getBuilder()->roundingMode(Money::EUR(1000), 0.21); //total is 1001
        $equals = $this->getBuilder()->roundingMode(Money::EUR(1100), 0.21); // total is 1100
        $up = $this->getBuilder()->roundingMode(Money::EUR(2100), 0.21); // total is 2099

        $this->assertSame(Money::ROUND_UP, $down);
        $this->assertSame(Money::ROUND_HALF_UP, $equals);
        $this->assertSame(Money::ROUND_DOWN, $up);
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
