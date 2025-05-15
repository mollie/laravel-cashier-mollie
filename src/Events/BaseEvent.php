<?php

declare(strict_types=1);

namespace Laravel\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\EventLog\LoggableEvent;

abstract class BaseEvent extends LoggableEvent
{
    use SerializesModels;
}
