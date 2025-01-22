<?php

namespace Laravel\Cashier\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Cashier\Order\OrderItem;
use Laravel\Cashier\Plan\Contracts\PlanRepository;
use Laravel\Cashier\Plan\Plan;
use Laravel\Cashier\Subscription;

class SyncSubscriptionPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashier:sync-plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update scheduled order items to reflect their current subscription plan\'s values';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(PlanRepository $planRepository)
    {
        $query = OrderItem::query()
            ->with('orderable')
            ->whereNull('processed_at')
            ->whereNotNull('orderable_type') // Support default model overrides
            ->whereNotNull('orderable_id');

        $countUpdated = 0;

        $query->chunk(100, function ($items) use ($planRepository, &$countUpdated) {
            foreach ($items as $item) {

                // Skip if not a subscription order item
                if (!($item->orderable instanceof Subscription)) {
                    continue;
                }

                $subscription = $item->orderable;

                // Get the current plan price
                /** @var Plan $plan */
                $plan = $planRepository->findOrFail($subscription->plan);
                $planAmount = $plan->amount()->getAmount();
                $planCurrency = $plan->amount()->getCurrency()->getCode();
                $planDescription = $plan->description();

                // Check if any of the plan values need updating
                if ($item->unit_price !== $planAmount
                    || $item->currency !== $planCurrency
                    || $item->description !== $planDescription
                ) {
                    $item->update([
                        'unit_price' => $planAmount,
                        'currency' => $planCurrency,
                        'description' => $planDescription,
                    ]);

                    $countUpdated++;

                    $descriptionChanged = $item->description !== $planDescription;
                    $descriptionPart = $descriptionChanged ? ' and description' : '';
                    $this->info(sprintf(
                        'Updated price%s for order item #%d (Subscription #%d) from %d to %d %s%s',
                        $descriptionPart,
                        $item->id,
                        $subscription->id,
                        $item->unit_price,
                        $planAmount,
                        $planCurrency,
                        $descriptionChanged ? ' and from "' . $item->description . '" to "' . $planDescription . '"' : '',
                    ));
                }
            }
        });

        $this->info("Updated prices for {$countUpdated} order items.");

        return 0;
    }
}
