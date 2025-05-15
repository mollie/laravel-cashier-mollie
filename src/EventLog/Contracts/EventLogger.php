<?php

declare(strict_types=1);

namespace Laravel\Cashier\EventLog\Contracts;

interface EventLogger
{
    public function log(Loggable $event): void;
}
