<?php

namespace Laravel\Cashier\Tests\Charge;

use Laravel\Cashier\Charge\FirstPaymentChargeBuilder;
use Laravel\Cashier\Charge\MandatedChargeBuilder;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Fixtures\User;

class ManageChargesTest extends BaseTestCase
{
    /** @test */
    public function using_mandated_charge_builder_when_valid_mandate()
    {
        $owner = User::factory()->create();

        $this->assertInstanceOf(FirstPaymentChargeBuilder::class, $owner->newCharge());
    }

    /** @test */
    public function use_new_mandated_charge()
    {
        $this->withMockedGetMollieCustomer(2);
        $this->withMockedGetMollieMandateAccepted(2);
        $owner = $this->getMandatedUser(true, [
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $this->assertInstanceOf(MandatedChargeBuilder::class, $owner->newCharge());
    }
}
