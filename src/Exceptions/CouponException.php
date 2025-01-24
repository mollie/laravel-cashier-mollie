<?php

namespace Laravel\Cashier\Exceptions;

use Exception;
use Throwable;

class CouponException extends Exception
{
    /**
     * CouponException constructor.
     */
    public function __construct(string $message, int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
