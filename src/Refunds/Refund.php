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
 * @property \Laravel\Cashier\Refunds\RefundItemCollection items
 * @property Order order
 * @property Order originalOrder
 */
class Refund extends Model
{
    use HasOwner;

    protected $guarded = [];

    protected $casts = [
        'total' => 'int',
    ];

    /**
     * Create a new Refund Collection instance.
     *
     * @param  array  $models
     * @return \Laravel\Cashier\Refunds\RefundCollection
     */
    public function newCollection(array $models = [])
    {
        return new RefundCollection($models);
    }

    /**
     * Scope the query to only include unprocessed refunds.
     *
     * @param $query
     * @return Builder
     */
    public function scopeWhereUnprocessed(Builder $query)
    {
        return $query->where('mollie_refund_status', RefundStatus::PENDING);
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
            /** @var static $refund */
            $refund = static::whereKey($this->getKey())->lockForUpdate()->firstOrFail();

            if ($refund->mollie_refund_status !== RefundStatus::PENDING) {
                return;
            }

            $refundItems = $refund->items;
            $orderItems = $refundItems->toNewOrderItemCollection()->save();
            $order = Cashier::$orderModel::createProcessedFromItems($orderItems);

            $refund->order_id = $order->id;
            $refund->mollie_refund_status = RefundStatus::REFUNDED;

            $refund->save();

            $refundItems->each(function (RefundItem $refundItem) {
                $originalOrderItem = $refundItem->originalOrderItem;

                if( $originalOrderItem && method_exists($originalOrderItem, 'handlePaymentRefunded') )
                {
                    $originalOrderItem->handlePaymentRefunded($refundItem);
                }
            });

            $refund->originalOrder->increment('amount_refunded', (int) $refundItems->getTotal()->getAmount());

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
            /** @var static $refund */
            $refund = static::whereKey($this->getKey())->lockForUpdate()->firstOrFail();

            if ($refund->mollie_refund_status !== RefundStatus::PENDING) {
                return;
            }

            $refundItems = $refund->items;
            $refund->mollie_refund_status = RefundStatus::FAILED;

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
