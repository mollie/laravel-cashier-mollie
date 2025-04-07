<?php

declare(strict_types=1);

namespace Laravel\Cashier\EventLog\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Loggable
{
    public function getLogCashierVersion(): string;

    public function getLogEventName(): string;

    public function getLogContext(): array;

    public function getLogBillable(): ?Model;

    public function getLogOrderable(): ?Model;
}
