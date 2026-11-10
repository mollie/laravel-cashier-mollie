<?php

namespace Laravel\Cashier\Order;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class OrderCollection extends EloquentCollection
{
    /**
     * Get the invoices for all orders in this collection.
     *
     * @return Collection
     */
    public function invoices()
    {
        return $this->map(function ($order) {
            return $order->invoice();
        });
    }
}
