<?php

namespace Laravel\Cashier\Contracts;

interface ProvidesOauthInformation
{
    /**
     * Get the OAuth token.
     */
    public function getOauthToken(): string;

    /**
     * Get the Mollie Profile ID.
     */
    public function getMollieProfile(): string;

    /**
     * Get whether the Mollie API is in test mode.
     */
    public function isMollieTestmode(): bool;
}
