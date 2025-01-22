<?php

namespace Laravel\Cashier\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Cashier\Order\OrderItem;
use Laravel\Cashier\Plan\Contracts\PlanRepository;
use Laravel\Cashier\Plan\Plan;
use Laravel\Cashier\Subscription;

class SyncSubscriptionPrices extends Command
{
    /**
     * @var PlanRepository
     */
    protected $planRepository;

    public function __construct(PlanRepository $planRepository)
    {
        parent::__construct();
        $this->planRepository = $planRepository;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashier:sync-subscription-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update prices of scheduled order items to match their current subscription plan prices';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query = OrderItem::query()
            ->with('orderable')
            ->whereNull('processed_at')
            ->whereNotNull('orderable_type') // Support default model overrides
            ->whereNotNull('orderable_id');

        $updated = 0;

        $query->chunk(100, function ($items) use (&$updated) {
            foreach ($items as $item) {
                
                // Skip if not a subscription order item
                if (!($item->orderable instanceof Subscription)) {
                    continue;
                }

                /** @var Subscription $subscription */
                $subscription = $item->orderable;
                
                // Get the current plan price
                /** @var Plan $plan */
                $plan = $this->planRepository->findOrFail($subscription->plan);
                $planAmount = $plan->amount()->getAmount();
                $planCurrency = $plan->amount()->getCurrency()->getCode();

                // Check if either the price or currency needs updating
                if ($item->unit_price !== $planAmount || $item->currency !== $planCurrency) {
                    $item->update([
                        'unit_price' => $planAmount,
                        'currency' => $planCurrency,
                    ]);
                    
                    $updated++;
                    
                    $this->info(sprintf(
                        'Updated price for order item #%d (Subscription #%d) from %d to %d %s',
                        $item->id,
                        $subscription->id,
                        $item->unit_price,
                        $planAmount,
                        $planCurrency,
                    ));
                }
            }
        });

        $this->info("Updated prices for {$updated} order items.");

        return 0;
    }
}
