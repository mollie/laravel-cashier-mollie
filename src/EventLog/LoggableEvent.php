<?php

declare(strict_types=1);

namespace Laravel\Cashier\EventLog;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\CashierServiceProvider;
use Laravel\Cashier\EventLog\Contracts\Loggable;

abstract class LoggableEvent implements Loggable
{
    public function getLogCashierVersion(): string
    {
        return CashierServiceProvider::PACKAGE_VERSION;
    }

    public function getLogEventName(): string
    {
        return static::class;
    }

    public function getLogContext(): array
    {
        return [];
    }

    public function getLogBillable(): ?Model
    {
        return null;
    }

    public function getLogOrderable(): ?Model
    {
        return null;
    }
}
