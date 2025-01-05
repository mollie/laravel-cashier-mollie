<?php

namespace Laravel\Cashier\FirstPayment\Actions;

use Illuminate\Database\Eloquent\Model;
use Money\Money;

class AddBalance extends AddGenericOrderItem
{
    /**
     * AddBalance constructor.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $owner
     * @param  \Money\Money  $subtotal
     * @param  int  $quantity
     * @param  string  $description
     */
    public function __construct(Model $owner, Money $subtotal, int $quantity, string $description)
    {
        parent::__construct($owner, $subtotal, $quantity, $description);

        $this->taxPercentage = 0; // Adding balance is NOT taxed by default
    }

    /**
     * Execute this action and return the created OrderItemCollection.
     *
     * @return \Laravel\Cashier\Order\OrderItemCollection
     */
    public function execute()
    {
        $this->owner->addCredit($this->getSubtotal());

        return parent::execute();
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

        return (new self(
            owner: $owner,
            subtotal: mollie_array_to_money($unit_price),
            quantity: $quantity,
            description: $payload['description'],
        ))->withTaxPercentage($taxPercentage);
    }
}
