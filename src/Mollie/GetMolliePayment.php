<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Laravel\Cashier\Mollie\Contracts\GetMolliePayment as Contract;
use Mollie\Api\Resources\Payment;

class GetMolliePayment extends BaseMollieInteraction implements Contract
{
    public function execute(string $id, array $parameters = [], ?ProvidesOauthToken $model = null): Payment
    {
        $this->setAccessToken($model);

        return $this->mollie->payments->get($id, $parameters + [
            'testmode' => ! app()->environment('production'),
        ]);
    }
}
