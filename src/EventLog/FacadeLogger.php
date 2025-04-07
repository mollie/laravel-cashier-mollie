<?php

declare(strict_types=1);

namespace Laravel\Cashier\EventLog;

use Illuminate\Support\Facades\Log;
use Laravel\Cashier\EventLog\Contracts\Loggable;
use Laravel\Cashier\EventLog\Contracts\EventLogger;

class FacadeLogger implements EventLogger
{
    public function log(Loggable $event): void
    {
        Log::info(
            $event->getLogEventName(),
            [
                'cashier_version' => $event->getLogCashierVersion(),
                'context' => $event->getLogContext(),
                'orderable' => $event->getLogOrderable(),
                'billable' => $event->getLogBillable(),
            ],
        );
    }
}
