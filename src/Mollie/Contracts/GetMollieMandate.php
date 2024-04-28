<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie\Contracts;

use Laravel\Cashier\Contracts\ProvidesOauthInformation;
use Mollie\Api\Resources\Mandate;

interface GetMollieMandate
{
    public function execute(string $customerId, string $mandateId, ?ProvidesOauthInformation $model = null): Mandate;
}
