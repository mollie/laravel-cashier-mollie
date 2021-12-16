<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Events\ChargebackReceived;
use Laravel\Cashier\Http\Controllers\AftercareWebhookController;
use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;
use Laravel\Cashier\Payment;
use Laravel\Cashier\Tests\BaseTestCase;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Payment as MolliePayment;

class AftercareWebhookControllerTest extends BaseTestCase
{
    /** @test */
    public function itDetectsNewChargebacks()
    {
        Event::fake();
        $this->withPackageMigrations();

        $molliePaymentId = 'tr_123xyz';
        $molliePayment = new MolliePayment(new MollieApiClient);
        $molliePayment->id = 'tr_dummy_payment_id';
        $molliePayment->status = 'paid';
        $molliePayment->amount = (object) [
            'currency' => 'EUR',
            'value' => '20.00',
        ];
        $molliePayment->amountRefunded = null;
        $molliePayment->amountChargedBack = null;
        $molliePayment->_links = (object) [];

        $localPayment = Payment::createFromMolliePayment($molliePayment, $this->getUser());
        $molliePayment->amountChargedBack = (object) [
            'value' => '10.00',
            'currency' => 'EUR',
        ];
        $molliePayment->_links->chargebacks = (object) [
            'href' => 'https://www.mollie.com/dashboard/org_12345678/payments/tr_WDqYK6vllg',
            'type' => 'application/json',
        ];

        $this->mock(GetMolliePayment::class, function (GetMolliePayment $mock) use ($molliePaymentId, $molliePayment) {
            return $mock->shouldReceive('execute')
                ->with($molliePaymentId, [])
                ->once()
                ->andReturn($molliePayment);
        });

        /** @var AftercareWebhookController $controller */
        $controller = $this->app->make(AftercareWebhookController::class);

        $controller->handleWebhook(
            Request::create('/', 'POST', ['id' => $molliePaymentId])
        );

        $localPayment->refresh();
        $this->assertMoney(1000, 'EUR', $localPayment->getAmountChargedBack());

        Event::assertDispatched(ChargebackReceived::class);
    }
}
