<?php

namespace Laravel\Cashier\Tests\Charge;

use Laravel\Cashier\Charge\ChargeItem;
use Laravel\Cashier\Charge\FirstPaymentChargeBuilder;
use Laravel\Cashier\Charge\MandatedChargeBuilder;
use Laravel\Cashier\Exceptions\MandateIsNotYetFinalizedException;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Fixtures\User;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\Attributes\Test;

class ManageChargesTest extends BaseTestCase
{
    #[Test]
    public function usingMandatedChargeBuilderWhenValidMandate()
    {
        $owner = User::factory()->create();

        $this->assertInstanceOf(FirstPaymentChargeBuilder::class, $owner->newCharge());
    }

    #[Test]
    public function useNewMandatedCharge()
    {
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);
        $owner = $this->getMandatedUser(true, [
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $this->assertInstanceOf(MandatedChargeBuilder::class, $owner->newCharge());
    }

    #[Test]
    public function mandatedChargeStillRejectsPendingMandate()
    {
        // Regression test for issue #289: while the first-payment flow accepts
        // a pending mandate (Mollie has already collected that payment),
        // off-session/recurring charges must remain strict — a pending mandate
        // offers no payment guarantee for charges initiated by the merchant.
        $this->expectException(MandateIsNotYetFinalizedException::class);

        $this->withMockedGetMollieCustomer();
        $this->withMockedGetMollieMandatePending();
        $owner = $this->getMandatedUser(true, [
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $builder = new MandatedChargeBuilder($owner);
        $builder->addItem(new ChargeItem(
            $owner,
            new Money(1000, new Currency('EUR')),
            'Test charge'
        ))->create();
    }
}
