<?php

declare(strict_types=1);

namespace Laravel\Cashier\Mollie;

use Laravel\Cashier\Contracts\ProvidesOauthToken;
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

    /**
     * Set the OAuth token to be used by the interaction.
     */
    public function setAccessToken(?ProvidesOauthToken $model = null): void
    {
        if ($model && ($token = $model->getOauthToken())) {
            $this->mollie->setAccessToken($token);
        }
    }
}
