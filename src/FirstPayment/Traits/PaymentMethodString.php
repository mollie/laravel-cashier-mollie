<?php

namespace Laravel\Cashier\FirstPayment\Traits;

trait PaymentMethodString
{
    /**
     * Backwards compatible: split strings into array
     *
     * @param  string  $method
     * @return string[]
     */
    private function castPaymentMethodString(string $method)
    {
        return collect(explode(',', $method))
            ->map(fn($methodString) => trim($methodString))
            ->filter()
            ->unique()
            ->all();
    }
}
