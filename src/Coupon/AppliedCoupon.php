<?php

namespace Laravel\Cashier\Coupon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Order\Contracts\InteractsWithOrderItems;
use Laravel\Cashier\Order\OrderItem;
use Laravel\Cashier\Order\OrderItemCollection;

/**
 * @method static create(array $array)
 *
 * @property OrderItemCollection $orderItems
 * @property array|null $metadata
 */
class AppliedCoupon extends Model implements InteractsWithOrderItems
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the model relation the coupon was applied to.
     *
     * @return MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * The OrderItem relation for this applied coupon.
     *
     * @return MorphMany
     */
    public function orderItems()
    {
        return $this->morphMany(Cashier::$orderItemModel, 'orderable');
    }

    /**
     * Called right before processing the order item into an order.
     *
     * @return OrderItemCollection
     */
    public static function preprocessOrderItem(OrderItem $item)
    {
        return $item->toCollection();
    }

    /**
     * Called after processing the order item into an order.
     *
     * @return OrderItem The order item that's being processed
     */
    public static function processOrderItem(OrderItem $item)
    {
        return $item;
    }

    /**
     * Handle a failed payment.
     *
     * @return void
     */
    public static function handlePaymentFailed(OrderItem $item)
    {
        $item->orderable->delete();
    }

    /**
     * Handle a paid payment.
     *
     * @return void
     */
    public static function handlePaymentPaid(OrderItem $item)
    {
        // All is taken care of
    }
}
