<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Mollie\Api\Resources\Payment;

interface GetMolliePayment
{
    /**
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function execute(string $id, array $parameters = []): Payment;
}
