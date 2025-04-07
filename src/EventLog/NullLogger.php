<?php

declare(strict_types=1);

namespace Laravel\Cashier\EventLog;

use Laravel\Cashier\EventLog\Contracts\Loggable;
use Laravel\Cashier\EventLog\Contracts\EventLogger;

class NullLogger implements EventLogger
{
    public function log(Loggable $event): void
    {
        // do nothing
    }
}
