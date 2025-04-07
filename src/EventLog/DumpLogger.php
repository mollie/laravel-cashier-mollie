<?php

declare(strict_types=1);

namespace Laravel\Cashier\EventLog;

use Laravel\Cashier\EventLog\Contracts\EventLogger;
use Laravel\Cashier\EventLog\Contracts\Loggable;

class DumpLogger implements EventLogger
{
    public function log(Loggable $event): void
    {
        dump([
            'cashier_version' => $event->getLogCashierVersion(),
            'event' => $event->getLogEventName(),
            'orderableType' => $event->getLogOrderable()?->getMorphClass(),
            'orderableId' => $event->getLogOrderable()?->getKey(),
            'billableType' => $event->getLogBillable()?->getMorphClass(),
            'billableId' => $event->getLogBillable()?->getKey(),
            'data' => $event->getLogContext(),
        ]);
    }
}
