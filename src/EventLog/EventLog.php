<?php

declare(strict_types=1);

namespace Laravel\Cashier\EventLog;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\EventLog\Contracts\Loggable;
use function json_encode;
use function optional;

/**
 * @property string $cashier_version
 * @property string $event
 * @property array $context
 * @property null|string $orderable_id
 * @property null|string $orderable_type
 * @property null|string $billable_id
 * @property null|string $billable_type
 */
class EventLog extends Model
{
    protected $table = 'event_log';
    protected $guarded = [];

    protected $casts = [
        'context' => 'array',
    ];

    static public function log(Loggable $event): void
    {
        $result = new static;

        $result->cashier_version = $event->getLogCashierVersion();
        $result->event = $event->getLogEventName();
        $result->context = json_encode($event->getLogContext());
        $result->orderable_id = optional($event->getLogOrderable())->id;
        $result->orderable_type = optional($event->getLogOrderable())->getMorphClass();
        $result->billable_id = optional($event->getLogBillable())->id;
        $result->billable_type = optional($event->getLogBillable())->getMorphClass();

        $result->save();
    }

    public function purge($keepDays = 180): void
    {
        $this->where('created_at', '<=', now()->subDays($keepDays))->delete();
    }
}
