<?php

declare(strict_types=1);

namespace Laravel\Cashier\Exceptions;

use Exception;
use Throwable;

class UnauthorizedInvoiceAccessException extends Exception
{
    /**
     * PlanNotFoundException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = 'Unauthorized invoice access', int $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
