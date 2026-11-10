<?php

namespace Laravel\Cashier\UpdatePaymentMethod\Contracts;

use Laravel\Cashier\Http\RedirectToCheckoutResponse;

interface UpdatePaymentMethodBuilder
{
    /**
     * Update payment method.
     *
     * @return RedirectToCheckoutResponse
     */
    public function create();
}
