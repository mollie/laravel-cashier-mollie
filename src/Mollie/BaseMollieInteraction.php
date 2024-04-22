<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Mollie\Api\MollieApiClient as Mollie;

abstract class BaseMollieInteraction
{
    /**
     * @var \Mollie\Api\MollieApiClient
     */
    protected $mollie;

    public function __construct(Mollie $mollie)
    {
        $this->mollie = $mollie;
    }
}
