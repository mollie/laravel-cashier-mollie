<?php

declare(strict_types=1);

namespace Laravel\Cashier\Refunds;

use Illuminate\Database\Eloquent\Collection;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Order\OrderItem;
use Laravel\Cashier\Order\OrderItemCollection;
use Money\Currency;
use Money\Money;

class RefundItemCollection extends Collection
{
    public static function makeFromOrderItemCollection(OrderItemCollection $orderItems, array $overrides = []): self
    {
        $refundItems = $orderItems->map(function (OrderItem $orderItem) use ($overrides) {
            return Cashier::$refundItemModel::makeFromOrderItem($orderItem, $overrides);
        })->all();

        return new static($refundItems);
    }

    public function getTotal(): Money
    {
        return new Money($this->sum('total'), new Currency($this->getCurrency()));

    }

    public function getCurrency(): string
    {
        return $this->first()->currency;
    }

    public function toNewOrderItemCollection(): OrderItemCollection
    {
        return new OrderItemCollection(
            $this->map(function (RefundItem $refundItem) {
                return Cashier::$orderItemModel::make([
                    'process_at' => now(),
                    'orderable_type' => $refundItem->getMorphClass(),
                    'orderable_id' => $refundItem->getKey(),
                    'owner_type' => $refundItem->owner_type,
                    'owner_id' => $refundItem->owner_id,
                    'description' => $refundItem->description,
                    'description_extra_lines' => $refundItem->description_extra_lines,
                    'currency' => $refundItem->currency,
                    'quantity' => $refundItem->quantity,
                    'unit_price' => -($refundItem->unit_price),
                    'tax_percentage' => $refundItem->tax_percentage,
                    'order_id' => null,
                ]);
            })
        );
    }
}
