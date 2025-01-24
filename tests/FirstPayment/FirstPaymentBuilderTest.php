<?php

namespace Laravel\Cashier\Tests\FirstPayment;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\FirstPayment\Actions\AddBalance;
use Laravel\Cashier\FirstPayment\Actions\AddGenericOrderItem;
use Laravel\Cashier\FirstPayment\FirstPaymentBuilder;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Fixtures\User;
use Mollie\Api\Resources\Payment as MolliePayment;
use Mollie\Api\Types\SequenceType;
use Money\Currency;
use Money\Money;

class FirstPaymentBuilderTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withMockedCreateMollieCustomer();
    }

    /** @test */
    public function can_build_payload()
    {
        $owner = User::factory()->create();
        $this->assertEquals(0, $owner->orderItems()->count());
        $this->assertEquals(0, $owner->orders()->count());

        $builder = new FirstPaymentBuilder($owner, [
            'description' => 'Test mandate payment',
            'redirectUrl' => 'https://www.example.com',
        ]);

        $builder->inOrderTo([
            new AddBalance(
                $owner,
                new Money(500, new Currency('EUR')),
                1,
                'Test add balance 1'
            ),
            new AddBalance(
                $owner,
                new Money(500, new Currency('EUR')),
                1,
                'Test add balance 2'
            ),
        ]);

        $payload = $builder->getMolliePayload();
        $customerId = $payload['customerId'];
        unset($payload['customerId']);
        $check_payload = [
            'sequenceType' => SequenceType::SEQUENCETYPE_FIRST,
            'description' => 'Test mandate payment',
            'amount' => [
                'value' => '10.00',
                'currency' => 'EUR',
            ],
            'redirectUrl' => 'https://www.example.com',
            'webhookUrl' => 'https://www.example.com/mandate-webhook',
            'metadata' => [
                'owner' => [
                    'type' => get_class($owner),
                    'id' => $owner->getKey(),
                ],
            ],
        ];

        $this->assertEquals($payload, $check_payload);
        $this->assertNotEmpty($customerId);
        $this->assertEquals(0, $owner->orderItems()->count());
        $this->assertEquals(0, $owner->orders()->count());
    }

    /** @test */
    public function creates_mollie_payment()
    {
        $owner = User::factory()->create();
        $this->assertEquals(0, $owner->orderItems()->count());
        $this->assertEquals(0, $owner->orders()->count());

        $builder = new FirstPaymentBuilder($owner, [
            'description' => 'Test mandate payment',
            'redirectUrl' => 'https://www.example.com',
        ]);

        $builder->inOrderTo([
            new AddBalance(
                $owner,
                new Money(500, new Currency('EUR')),
                1,
                'Test add balance 1'
            ),
            new AddBalance(
                $owner,
                new Money(500, new Currency('EUR')),
                1,
                'Test add balance 2'
            ),
        ]);

        $this->withMockedCreateMolliePayment(1, '12.34');

        $payment = $builder->create();

        $this->assertEquals(0, $owner->orderItems()->count());
        $this->assertEquals(0, $owner->orders()->count());

        $this->assertInstanceOf(MolliePayment::class, $payment);
    }

    /** @test */
    public function parses_redirect_url_payment_id_upon_payment_creation()
    {
        $owner = User::factory()->create();

        $builder = new FirstPaymentBuilder($owner, [
            'redirectUrl' => 'https://www.example.com/{payment_id}',
        ]);

        $this->withMockedCreateMolliePayment(1, '12.34');
        $this->withMockedUpdateMolliePayment(1, '12.34', 'https://www.example.com/tr_unique_id');

        $payment = $builder->inOrderTo([
            new AddGenericOrderItem($owner, new Money(100, new Currency('EUR')), 1, 'Parse redirectUrl test'),
        ])->create();

        $this->assertEquals('https://www.example.com/tr_unique_id', $payment->redirectUrl);
    }

    /** @test */
    public function stores_local_payment_record()
    {
        $owner = User::factory()->create();
        $this->assertEquals(0, $owner->orderItems()->count());
        $this->assertEquals(0, $owner->orders()->count());

        $builder = new FirstPaymentBuilder($owner, [
            'description' => 'Test mandate payment',
            'redirectUrl' => 'https://www.example.com',
        ]);

        $builder->inOrderTo([
            new AddBalance(
                $owner,
                new Money(500, new Currency('EUR')),
                1,
                'Test add balance 1'
            ),
            new AddBalance(
                $owner,
                new Money(500, new Currency('EUR')),
                1,
                'Test add balance 2'
            ),
        ]);

        $this->withMockedCreateMolliePayment(1, '12.34', 'tr_dummy_payment_id');

        $molliePayment = $builder->create();

        $localPayment = Cashier::$paymentModel::findByPaymentIdOrFail($molliePayment->id);
        $this->assertNull($localPayment->order_id);
        $this->assertEquals('tr_dummy_payment_id', $localPayment->mollie_payment_id);
        $this->assertEquals('open', $localPayment->mollie_payment_status);
        $this->assertTrue($localPayment->owner->is($owner));
        $this->assertEquals('EUR', $localPayment->currency);
        $this->assertEquals(1234, $localPayment->amount);
        $this->assertEquals(0, $localPayment->amount_refunded);
        $this->assertEquals(0, $localPayment->amount_charged_back);
    }
}
