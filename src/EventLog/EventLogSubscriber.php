<?php

declare(strict_types=1);

namespace Laravel\Cashier\EventLog;

use Laravel\Cashier\EventLog\Contracts\EventLogger;
use Laravel\Cashier\EventLog\Contracts\Loggable;

class EventLogSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        return [
            \Laravel\Cashier\Events\BalanceTurnedStale::class => 'handle',
            \Laravel\Cashier\Events\ChargebackReceived::class => 'handle',
            \Laravel\Cashier\Events\CouponApplied::class => 'handle',
            \Laravel\Cashier\Events\FirstPaymentFailed::class => 'handle',
            \Laravel\Cashier\Events\FirstPaymentPaid::class => 'handle',
            \Laravel\Cashier\Events\MandateClearedFromBillable::class => 'handle',
            \Laravel\Cashier\Events\MandateUpdated::class => 'handle',
            \Laravel\Cashier\Events\OrderCreated::class => 'handle',
            \Laravel\Cashier\Events\OrderInvoiceAvailable::class => 'handle',
            \Laravel\Cashier\Events\OrderPaymentFailed::class => 'handle',
            \Laravel\Cashier\Events\OrderPaymentFailedDueToInvalidMandate::class => 'handle',
            \Laravel\Cashier\Events\OrderPaymentPaid::class => 'handle',
            \Laravel\Cashier\Events\OrderProcessed::class => 'handle',
            \Laravel\Cashier\Events\RefundFailed::class => 'handle',
            \Laravel\Cashier\Events\RefundInitiated::class => 'handle',
            \Laravel\Cashier\Events\RefundProcessed::class => 'handle',
            \Laravel\Cashier\Events\SubscriptionCancelled::class => 'handle',
            \Laravel\Cashier\Events\SubscriptionPlanSwapped::class => 'handle',
            \Laravel\Cashier\Events\SubscriptionQuantityUpdated::class => 'handle',
            \Laravel\Cashier\Events\SubscriptionResumed::class => 'handle',
            \Laravel\Cashier\Events\SubscriptionStarted::class => 'handle',
        ];
    }

    public function handle(Loggable $event): void
    {
        /** @var $logger \Laravel\Cashier\EventLog\Contracts\EventLogger */
        $logger = app(EventLogger::class);

        $logger->log($event);
    }
}
