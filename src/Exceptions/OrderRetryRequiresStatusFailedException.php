<?php

namespace Laravel\Cashier\Exceptions;

use Exception;
use Throwable;

class OrderRetryRequiresStatusFailedException extends Exception
{
    /**
     * RetryingRequiresOrderStatusFailedException constructor.
     */
    public function __construct(string $message = 'The order status is not failed', int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
