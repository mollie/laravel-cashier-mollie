<?php

namespace Laravel\Cashier\FirstPayment\Actions;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Order\OrderItemCollection;
use Money\Money;

class AddBalance extends AddGenericOrderItem
{
    /**
     * AddBalance constructor.
     */
    public function __construct(Model $owner, Money $subtotal, int $quantity, string $description)
    {
        parent::__construct($owner, $subtotal, $quantity, $description);

        $this->taxPercentage = 0; // Adding balance is NOT taxed by default
    }

    /**
     * Execute this action and return the created OrderItemCollection.
     *
     * @return OrderItemCollection
     */
    public function execute()
    {
        $this->owner->addCredit($this->getSubtotal());

        return parent::execute();
    }
}
