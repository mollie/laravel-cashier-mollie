<?php

namespace Laravel\Cashier\Exceptions;

use Exception;
use Throwable;

class OrderStatusIsNotFailedException extends Exception
{
    /**
     * PlanNotFoundException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = 'The order status is not failed', int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
