<?php

declare(strict_types=1);

namespace Laravel\Cashier\EventLog\Contracts;

use Illuminate\Database\Eloquent\Model;

interface EventLogger
{
    public function log(Loggable $event): void;
}
