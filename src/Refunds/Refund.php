<?php

declare(strict_types=1);

namespace Laravel\Cashier\Refunds;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\RefundFailed;
use Laravel\Cashier\Events\RefundProcessed;
use Laravel\Cashier\Order\Order;
use Laravel\Cashier\Traits\HasOwner;
use Mollie\Api\Types\RefundStatus;

/**
 * @property int id
 * @property string mollie_refund_id
 * @property string mollie_refund_status
 * @property string owner_type
 * @property int owner_id
 * @property int|null original_order_item_id
 * @property int|null original_order_id
 * @property int|null order_id
 * @property RefundItemCollection items
 * @property Order order
 * @property Order originalOrder
 * @property array|null metadata
 */
class Refund extends Model
{
    use HasOwner;

    public const UNPROCESSED_STATUSES = ['pending', 'queued', 'processing'];

    protected $guarded = [];

    protected $casts = [
        'total' => 'int',
        'metadata' => 'array',
    ];

    /**
     * Create a new Refund Collection instance.
     *
     * @return RefundCollection
     */
    public function newCollection(array $models = [])
    {
        return new RefundCollection($models);
    }

    /**
     * Scope the query to only include unprocessed refunds.
     *
     * @return Builder
     */
    public function scopeWhereUnprocessed(Builder $query)
    {
        return $query->whereIn('mollie_refund_status', self::UNPROCESSED_STATUSES);
    }

    public function isLocal(): bool
    {
        return str_starts_with($this->mollie_refund_id, 'local_');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Cashier::$refundItemModel);
    }

    public function originalOrder(): HasOne
    {
        return $this->hasOne(Cashier::$orderModel, 'id', 'original_order_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Cashier::$orderModel, 'id', 'order_id');
    }

    public function handleProcessed(): self
    {
        $handled = false;

        DB::transaction(function () use (&$handled) {
            // Aftercare retries can process the same pending refund twice unless the refund row is
            // locked and re-checked before creating the compensating order.
            /** @var static $refund */
            $refund = static::whereKey($this->getKey())->lockForUpdate()->firstOrFail();

            if (! in_array($refund->mollie_refund_status, self::UNPROCESSED_STATUSES, true)) {
                return;
            }

            $refundItems = $refund->items;
            $orderItems = $refundItems->toNewOrderItemCollection()->save();
            $order = Cashier::$orderModel::createProcessedFromItems($orderItems);

            $refund->order_id = $order->id;
            $refund->mollie_refund_status = RefundStatus::Refunded->value;

            $refund->save();

            $refundItems->each(function (RefundItem $refundItem) {
                $originalOrderItem = $refundItem->originalOrderItem;

                if ($originalOrderItem && method_exists($originalOrderItem, 'handlePaymentRefunded')) {
                    $originalOrderItem->handlePaymentRefunded($refundItem);
                }
            });

            // Lock the order too: concurrent refunds on the same order would otherwise both
            // read the same amount_refunded and restore the same credit twice.
            /** @var Order $originalOrder */
            $originalOrder = Cashier::$orderModel::whereKey($refund->original_order_id)
                ->lockForUpdate()
                ->firstOrFail();

            $creditRestoredBefore = $originalOrder->getCreditUsedRestored();

            $originalOrder->increment('amount_refunded', (int) $refundItems->getTotal()->getAmount());

            // Whatever this refund reverses beyond the amount charged through Mollie was paid
            // from the owner's credit balance, so it goes back there. Deriving it from
            // amount_refunded means a refund that never gets processed never moves credit.
            $creditRestored = $originalOrder->refresh()
                ->getCreditUsedRestored()
                ->subtract($creditRestoredBefore);

            if ($creditRestored->isPositive()) {
                $refund->owner->addCredit($creditRestored);
            }

            $handled = true;
        });

        $this->refresh();

        if ($handled) {
            event(new RefundProcessed($this));
        }

        return $this;
    }

    public function handleFailed(): self
    {
        $handled = false;

        DB::transaction(function () use (&$handled) {
            // Only a pending refund may transition to failed; locking keeps duplicate deliveries
            // from re-running refund item failure hooks.
            /** @var static $refund */
            $refund = static::whereKey($this->getKey())->lockForUpdate()->firstOrFail();

            if (! in_array($refund->mollie_refund_status, self::UNPROCESSED_STATUSES, true)) {
                return;
            }

            $refundItems = $refund->items;
            $refund->mollie_refund_status = RefundStatus::Failed->value;

            $refund->save();

            $refundItems->each(function (RefundItem $refundItem) {
                $refundItem->originalOrderItem->handlePaymentRefundFailed($refundItem);
            });

            $handled = true;
        });

        $this->refresh();

        if ($handled) {
            event(new RefundFailed($this));
        }

        return $this;
    }
}
