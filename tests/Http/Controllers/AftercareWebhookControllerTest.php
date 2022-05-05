<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\ChargebackReceived;
use Laravel\Cashier\Events\RefundProcessed;
use Laravel\Cashier\Http\Controllers\AftercareWebhookController;
use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Refunds\RefundItemCollection;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Fixtures\User;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Payment as MolliePayment;
use Mollie\Api\Resources\Refund as MollieRefund;
use Mollie\Api\Types\PaymentStatus as MolliePaymentStatus;
use Mollie\Api\Types\RefundStatus as MollieRefundStatus;

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

        $localPayment = Cashier::$paymentModel::createFromMolliePayment($molliePayment, $this->getUser());
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
            $this->getWebhookRequest($molliePaymentId)
        );

        $localPayment->refresh();
        $this->assertMoney(1000, 'EUR', $localPayment->getAmountChargedBack());

        Event::assertDispatched(ChargebackReceived::class);
    }

    /** @test */
    public function itDetectsNewRefunds()
    {
        Event::fake();
        $this->withPackageMigrations();
        $this->withConfiguredPlans();

        $molliePaymentId = 'tr_123xyz';
        $mollieRefundId = 're_456abc';

        $user = factory(User::class)->create();

        $subscription = $user->subscriptions()->save(factory(Cashier::$subscriptionModel)->make([
            'plan' => 'monthly-10-1',
        ]));

        $orderItems = new OrderItemCollection([
            $subscription->scheduleNewOrderItemAt(now()),
        ]);

        /** @var \Laravel\Cashier\Order\Order */
        $originalOrder = Cashier::$orderModel::createFromItems($orderItems, [
            'mollie_payment_id' => $molliePaymentId,
            'mollie_payment_status' => 'paid',
        ]);

        /** @var \Laravel\Cashier\Refunds\Refund */
        $localRefund = $originalOrder->refunds()->create([
            'owner_id' => $user->id,
            'owner_type' => get_class($user),
            'mollie_refund_id' => $mollieRefundId,
            'mollie_refund_status' => MollieRefundStatus::STATUS_PENDING,
            'total' => 1000,
            'currency' => 'EUR',
        ]);

        $localRefund->items()->saveMany(
            RefundItemCollection::makeFromOrderItemCollection($orderItems)
        );

        $this->assertTrue($originalOrder->refunds->contains('id', $localRefund->id));

        $mollieRefund = new MollieRefund(new MollieApiClient);
        $mollieRefund->id = $mollieRefundId;
        $mollieRefund->status = MollieRefundStatus::STATUS_REFUNDED;

        $molliePayment = $this->getMockBuilder(MolliePayment::class)
            ->setConstructorArgs([new MollieApiClient])
            ->onlyMethods(['refunds'])
            ->getMock();

        $molliePayment
            ->method('refunds')
            ->willReturn([$mollieRefund]);

        $molliePayment->id = $molliePaymentId;
        $molliePayment->status = MolliePaymentStatus::STATUS_PAID;
        $molliePayment->amount = (object) [
            'currency' => 'EUR',
            'value' => '10.00',
        ];
        $molliePayment->amountRefunded = null;
        $molliePayment->amountChargedBack = null;
        $molliePayment->_links = (object) [];

        $localPayment = Cashier::$paymentModel::createFromMolliePayment($molliePayment, $user);

        $molliePayment->amountRefunded = (object) [
            'value' => '10.00',
            'currency' => 'EUR',
        ];
        $molliePayment->_links->refunds = (object) [
            'href' => 'https://api.mollie.com/v2/payments/tr_WDqYK6vllg/refunds',
            'type' => 'application/hal+json',
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
            $this->getWebhookRequest($molliePaymentId)
        );

        Event::assertDispatched(RefundProcessed::class);

        $localPayment->refresh();
        $this->assertMoney(1000, 'EUR', $localPayment->getAmountRefunded());

        $localRefund->refresh();
        $this->assertEquals('EUR', $localRefund->currency);
        $this->assertEquals(1000, $localRefund->total);
        $this->assertNotNull($localRefund->order_id);

        $originalOrder->refresh();
        $this->assertMoney(1000, 'EUR', $originalOrder->getAmountRefunded());
        $this->assertEquals($originalOrder->id, $localRefund->originalOrder->id);
        $this->assertNotEquals($originalOrder->id, $localRefund->order->id);

        $refundOrder = $localRefund->order;
        $this->assertMoney(-1000, 'EUR', $refundOrder->getTotal());
    }

    /**
     * Get a request that mimics Mollie calling the webhook.
     *
     * @param $id
     * @return Request
     */
    protected function getWebhookRequest($id)
    {
        return Request::create('/', 'POST', ['id' => $id]);
    }
}
