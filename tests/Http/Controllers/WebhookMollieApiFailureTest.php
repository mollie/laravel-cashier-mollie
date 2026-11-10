<?php

namespace Laravel\Cashier\Tests\Http\Controllers;

use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;
use Laravel\Cashier\Tests\BaseTestCase;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

class WebhookMollieApiFailureTest extends BaseTestCase
{
    public static function webhookRoutes(): array
    {
        return [
            'first payment' => ['webhooks.mollie.first_payment'],
            'renewal order' => ['webhooks.mollie.default'],
        ];
    }

    /**
     * Mollie sends only the payment id and retries the webhook until it gets a 2xx. When fetching that payment
     * fails on Mollie's side, acknowledging the webhook drops the payment for good.
     */
    #[Test]
    #[DataProvider('webhookRoutes')]
    public function a_mollie_server_error_while_fetching_the_payment_is_not_acknowledged(string $route)
    {
        $this->withMockedGetMolliePaymentFailingWithStatus('tr_paid_payment', 502);
        $this->assertFalse(config('app.debug'));

        $response = $this->post(route($route), ['id' => 'tr_paid_payment']);

        $this->assertGreaterThanOrEqual(500, $response->getStatusCode());
    }

    #[Test]
    #[DataProvider('webhookRoutes')]
    public function an_unknown_payment_id_is_still_answered_with200(string $route)
    {
        $this->withMockedGetMolliePaymentFailingWithStatus('tr_unknown', 404);

        $response = $this->post(route($route), ['id' => 'tr_unknown']);

        $response->assertStatus(200);
    }

    protected function withMockedGetMolliePaymentFailingWithStatus(string $id, int $status)
    {
        $this->mock(GetMolliePayment::class, function ($mock) use ($id, $status) {
            return $mock->shouldReceive('execute')
                ->with($id, [])
                ->once()
                ->andThrow(new ApiException(
                    \Mockery::mock(Response::class, [
                        'json' => (object) ['status' => $status, 'title' => 'Error', '_links' => (object) []],
                        'getPsrRequest' => \Mockery::mock(RequestInterface::class, [
                            'getBody' => \Mockery::mock(StreamInterface::class, [
                                '__toString' => '',
                            ]),
                        ]),
                    ]),
                    'Test exception',
                    $status,
                ));
        });
    }
}
