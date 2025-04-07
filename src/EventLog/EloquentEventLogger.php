<?php

declare(strict_types=1);

namespace Laravel\Cashier\EventLog;

use Laravel\Cashier\EventLog\Contracts\EventLogger;
use Laravel\Cashier\EventLog\Contracts\Loggable;

class EloquentEventLogger implements EventLogger
{
    public function log(Loggable $event): void
    {
        if (app()->runningInConsole()) {
            EventLog::log($event); // Run sync
        } else {
            defer(fn () => EventLog::log($event)); // Run deferred
        }
    }
}
