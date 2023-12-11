<?php

namespace Laravel\Cashier\Tests\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\OrderPaymentFailed;
use Laravel\Cashier\Events\OrderPaymentPaid;
use Laravel\Cashier\Events\SubscriptionCancelled;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;
use Laravel\Cashier\Mollie\Contracts\UpdateMolliePayment;
use Laravel\Cashier\Order\OrderItemCollection;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderFactory;
use Laravel\Cashier\Tests\Database\Factories\SubscriptionFactory;
use Laravel\Cashier\Tests\Fixtures\User;
use Laravel\Cashier\Types\SubscriptionCancellationReason;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Payment as MolliePayment;

class WebhookControllerTest extends BaseTestCase
{
    /** @test */
    public function retrievesPaymentResource()
    {
        $payment = new MolliePayment(resolve(MollieApiClient::class));
        $payment->id = 'tr_123xyz';

        $this->withMockedGetMolliePayment(1, $payment);

        $this->assertInstanceOf(MolliePayment::class, $this->getController()->getMolliePaymentById($payment->id));
    }

    /** @test **/
    public function MollieApiExceptionIsCatchedWhenDebugDisabled()
    {
        $wrongId = 'sub_xxxxxxxxxxx';
        $this->withMockedGetMolliePaymentThrowingException(1, $wrongId);

        $this->assertFalse(config('app.debug'));
        $this->assertNull($this->getController()->getMolliePaymentById($wrongId));
    }

    /** @test **/
    public function MollieApiExceptionIsThrownWhenDebugEnabled()
    {
        $wrongId = 'sub_xxxxxxxxxxx';
        $this->withMockedGetMolliePaymentThrowingException(1, $wrongId);

        config(['app.debug' => true]);
        $this->assertTrue(config('app.debug'));
        $this->expectException(ApiException::class);
        $this->assertNull($this->getController()->getMolliePaymentById($wrongId));
    }

    /** @test **/
    public function handlesUnexistingIdGracefully()
    {
        $wrongId = 'sub_xxxxxxxxxxx';
        $request = $this->getWebhookRequest($wrongId);
        $this->withMockedGetMolliePaymentThrowingException(1, $wrongId);

        $response = $this->getController()->handleWebhook($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test **/
    public function handlesPaymentFailed()
    {
        $this->withPackageMigrations();
        $this->withConfiguredPlans();
        $this->withTestNow('2019-01-01');
        Event::fake();

        $user = User::factory()->create();
        $subscription = $user->subscriptions()->save(SubscriptionFactory::new()->make([
            'plan' => 'monthly-10-1',
        ]));
        $item = $subscription->scheduleNewOrderItemAt(now());

        $paymentId = 'tr_failed_payment_id';

        $order = Cashier::$orderModel::createFromItems(new OrderItemCollection([$item]), [
            'mollie_payment_id' => $paymentId,
            'mollie_payment_status' => 'open',
            'balance_before' => 500,
            'credit_used' => 500,
        ]);
        $this->assertMoneyEURCents(0, $order->getBalanceAfter());

        $this->assertFalse($user->hasCredit('EUR'));

        $request = $this->getWebhookRequest($paymentId);

        $payment = new MolliePayment(new MollieApiClient);
        $payment->id = $paymentId;
        $payment->status = 'open';
        $payment->amount = (object) [
            'currency' => 'EUR',
            'value' => '10.00',
        ];
        $payment->mandateId = 'mdt_dummy_mandate_id';

        Cashier::$paymentModel::createFromMolliePayment($payment, $user);
        $payment->status = 'failed';

        $this->withMockedGetMolliePayment(1, $payment);

        $response = $this->makeTestResponse($this->getController()->handleWebhook($request));

        $response->assertStatus(200);

        $order = $order->fresh();
        $this->assertEquals('failed', $order->mollie_payment_status);
        $subscription = $subscription->fresh();
        $this->assertTrue($subscription->cancelled());
        $this->assertFalse($subscription->active());

        // Credits are restored to user balance.
        $this->assertMoneyEURCents(0, $order->getBalanceBefore());
        $this->assertMoneyEURCents(0, $order->getBalanceAfter());
        $this->assertMoneyEURCents(0, $order->getCreditUsed());
        $this->assertMoneyEURCents(500, $user->credit('EUR')->money());

        $this->assertEquals('failed', Cashier::$paymentModel::first()->mollie_payment_status);

        Event::assertDispatched(OrderPaymentFailed::class, function (OrderPaymentFailed $event) use ($order) {
            return $event->order->is($order)
                && $event->payment->is($order->payments->first());
        });

        Event::assertDispatched(SubscriptionCancelled::class, function (SubscriptionCancelled $event) use ($subscription) {
            $this->assertTrue($event->subscription->is($subscription));
            $this->assertEquals($event->reason, SubscriptionCancellationReason::PAYMENT_FAILED);

            return true;
        });
    }

    /** @test **/
    public function handlesPaymentPaid()
    {
        $this->withPackageMigrations();
        $this->withConfiguredPlans();
        Event::fake();

        $user = User::factory()->create();
        $subscription = $user->subscriptions()->save(SubscriptionFactory::new()->make([
            'plan' => 'monthly-10-1',
        ]));
        $item = $subscription->scheduleNewOrderItemAt(now());

        $order = Cashier::$orderModel::createFromItems(new OrderItemCollection([$item]));

        $paymentId = 'tr_payment_paid_id';

        $order->update([
            'mollie_payment_id' => $paymentId,
            'mollie_payment_status' => 'open',
        ]);

        $request = $this->getWebhookRequest($paymentId);

        $payment = new MolliePayment(new MollieApiClient);
        $payment->id = $paymentId;
        $payment->status = 'open';
        $payment->amount = (object) [
            'currency' => 'EUR',
            'value' => '10.00',
        ];
        $payment->mandateId = 'mdt_dummy_mandate_id';
        Cashier::$paymentModel::createFromMolliePayment($payment, $user);
        $payment->status = 'paid';

        $this->withMockedGetMolliePayment(1, $payment);
        $this->withMockedUpdateMolliePayment();

        $response = $this->getController()->handleWebhook($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('paid', $order->fresh()->mollie_payment_status);
        $this->assertTrue($subscription->fresh()->active());

        $this->assertEquals('paid', Cashier::$paymentModel::first()->mollie_payment_status);

        Event::assertDispatched(OrderPaymentPaid::class, function (OrderPaymentPaid $event) use ($order) {
            return $event->order->is($order)
                && $event->payment->is($order->payments->first());
        });
    }

    /** @test **/
    public function skipsIfPaymentStatusUnchanged()
    {
        $this->withPackageMigrations();
        Event::fake();

        $paymentId = 'tr_payment_paid_id';

        OrderFactory::new()->create([
            'mollie_payment_id' => $paymentId,
            'mollie_payment_status' => 'paid',
        ]);

        $request = $this->getWebhookRequest($paymentId);

        $payment = new MolliePayment(new MollieApiClient);
        $payment->id = $paymentId;
        $payment->status = 'paid';
        $payment->mandateId = 'mdt_dummy_mandate_id';
        $this->withMockedGetMolliePayment(1, $payment);

        $response = $this->getController()->handleWebhook($request);

        $this->assertEquals(200, $response->getStatusCode());
        Event::assertNotDispatched(OrderPaymentPaid::class);
        Event::assertNotDispatched(OrderPaymentFailed::class);
    }

    protected function getController(): WebhookController
    {
        return $this->app->make(WebhookController::class);
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

    protected function makeTestResponse($response)
    {
        if (class_exists('\Illuminate\Foundation\Testing\TestResponse')) {
            // Prior to Laravel v7
            return new \Illuminate\Foundation\Testing\TestResponse($response);
        }

        // Laravel v7
        return new \Illuminate\Testing\TestResponse($response);
    }
}
