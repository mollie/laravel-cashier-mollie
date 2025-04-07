<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Mollie\Api\Resources\Payment;

interface GetMolliePayment
{
    /**
     * @param string $id
     * @param array $parameters
     * @return \Mollie\Api\Resources\Payment
     *
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function execute(string $id, array $parameters = []): Payment;
}
