<?php

namespace Laravel\Cashier\Exceptions;

use Exception;
use Throwable;

class MandateIsNotYetFinalizedException extends Exception
{
    /**
     * PlanNotFoundException constructor.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(string $message = 'The customer mandate is still pending', int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
