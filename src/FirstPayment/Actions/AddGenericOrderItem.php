<?php

namespace Laravel\Cashier\FirstPayment\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Money\Money;

class AddGenericOrderItem extends BaseAction
{
    /**
     * AddGenericOrderItem constructor.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $owner
     * @param  \Money\Money  $unitPrice
     * @param  int  $quantity
     * @param  string  $description
     * @param  ?string  $metadata
     * @param  int  $roundingMode
     */
    public function __construct(
        Model $owner,
        Money $unitPrice,
        int $quantity,
        string $description,
        int $roundingMode = Money::ROUND_HALF_UP,
        ?string $metadata = null,
    ) {
        $this->owner = $owner;
        $this->taxPercentage = $this->owner->taxPercentage();
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
        $this->currency = $unitPrice->getCurrency()->getCode();
        $this->description = $description;
        $this->roundingMode = $roundingMode;
        $this->metadata = $metadata;
    }

    /**
     * @param  array  $payload
     * @param  \Illuminate\Database\Eloquent\Model  $owner
     * @return self
     */
    public static function createFromPayload(array $payload, Model $owner)
    {
        $taxPercentage = $payload['taxPercentage'] ?? 0;
        $quantity = $payload['quantity'] ?? 1;
        $unit_price = $payload['subtotal'] ?? $payload['unit_price'];
        $metadata = $payload['metadata'] ?? null;

        return (new self(
            owner: $owner,
            unitPrice: mollie_array_to_money($unit_price),
            quantity: $quantity,
            description: $payload['description'],
            metadata: $metadata,
        ))->withTaxPercentage($taxPercentage);
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [
            'handler' => static::class,
            'description' => $this->getDescription(),
            'unit_price' => money_to_mollie_array($this->getUnitPrice()),
            'quantity' => $this->getQuantity(),
            'taxPercentage' => $this->getTaxPercentage(),
            'metadata' => $this->metadata,
        ];
    }

    /**
     * Prepare a stub of OrderItems processed with the payment.
     *
     * @return \Laravel\Cashier\Order\OrderItemCollection
     */
    public function makeProcessedOrderItems()
    {
        return $this->owner->orderItems()->make([
            'description' => $this->getDescription(),
            'currency' => $this->getCurrency(),
            'process_at' => now(),
            'unit_price' => $this->getUnitPrice()->getAmount(),
            'tax_percentage' => $this->getTaxPercentage(),
            'quantity' => $this->getQuantity(),
            'metadata' => $this->metadata,
        ])->toCollection();
    }

    /**
     * Execute this action and return the created OrderItemCollection.
     *
     * @return \Laravel\Cashier\Order\OrderItemCollection
     */
    public function execute()
    {
        return tap($this->makeProcessedOrderItems(), function ($items) {
            $items->save();
        });
    }
}
