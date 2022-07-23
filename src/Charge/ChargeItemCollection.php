<?php

namespace Laravel\Cashier\Charge;

use Illuminate\Support\Collection;
use Laravel\Cashier\FirstPayment\Actions\ActionCollection as FirstPaymentActionCollection;
use Laravel\Cashier\Order\OrderItemCollection;

class ChargeItemCollection extends Collection
{
    public function toFirstPaymentActionCollection(): FirstPaymentActionCollection
    {
        $result = $this->map(fn(ChargeItem $item) => $item->toFirstPaymentAction());

        return new FirstPaymentActionCollection($result->all());
    }

    public function toOrderItemCollection(array $overrideOnEachItem = []): OrderItemCollection
    {
        $result = $this->map(fn(ChargeItem $item) => $item->toOrderItem($overrideOnEachItem));

        return new OrderItemCollection($result->all());
    }
}
