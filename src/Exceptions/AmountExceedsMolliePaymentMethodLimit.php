<?php

namespace Laravel\Cashier\Exceptions;

use Exception;
use Throwable;

class AmountExceedsMolliePaymentMethodLimit extends Exception
{
    /**
     * RetryingRequiresOrderStatusFailedException constructor.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(string $message = 'The order total is greater than the total accepted by Mollie', int $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
