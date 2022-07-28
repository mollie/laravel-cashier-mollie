<?php

namespace Laravel\Cashier\Order;

use Illuminate\Support\Collection;
use Illuminate\Support\Collection as BaseCollection;
use Laravel\Cashier\Order\BaseOrderItemPreprocessor as Preprocessor;

/**
 * A collection of instantiable OrderItemPreprocessor class strings.
 */
class OrderItemPreprocessorCollection extends Collection
{
    /**
     * Initialize the preprocessors from a string array.
     *
     * @param  string[]  $value
     * @return \Laravel\Cashier\Order\OrderItemPreprocessorCollection
     */
    public static function fromArray($value)
    {
        $preprocessors = collect($value)->map(fn($class) => app()->make($class));

        return static::fromBaseCollection($preprocessors);
    }

    /**
     * @param  \Laravel\Cashier\Order\OrderItem  $item
     * @return \Laravel\Cashier\Order\OrderItemCollection
     */
    public function handle(OrderItem $item)
    {
        $items = $this->reduce(fn($carry, Preprocessor $preprocessor) => $preprocessor->handle($carry), $item->toCollection());

        return new OrderItemCollection($items);
    }

    /**
     * Create an OrderItemCollection from a basic Collection.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @return \Laravel\Cashier\Order\OrderItemPreprocessorCollection
     */
    public static function fromBaseCollection(BaseCollection $collection)
    {
        return new static($collection->all());
    }
}
