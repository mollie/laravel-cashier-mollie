<?php

namespace Laravel\Cashier\Exceptions;

use Exception;
use Throwable;

class CurrencyMismatchException extends Exception
{
    /**
     * PlanNotFoundException constructor.
     */
    public function __construct(string $message = 'Currencies do not match', int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
