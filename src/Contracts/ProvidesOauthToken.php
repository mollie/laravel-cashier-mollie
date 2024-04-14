<?php

namespace Laravel\Cashier\Contracts;

interface ProvidesOauthToken
{
    /**
     * Get the OAuth token.
     */
    public function getOauthToken(): string;

    /**
     * Get the Mollie Profile ID.
     */
    public function getMollieProfile(): string;
}
