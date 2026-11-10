<?php

namespace Laravel\Cashier\Order;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as BaseCollection;
use Laravel\Cashier\Cashier;
use LogicException;
use Money\Currency;
use Money\Money;

class OrderItemCollection extends Collection
{
    /**
     * Get a collection of distinct currencies in this collection.
     *
     * @return BaseCollection
     */
    public function currencies()
    {
        return collect(array_values($this->pluck('currency')->unique()->all()));
    }

    /**
     * Get the distinct owners for this collection.
     *
     * @return BaseCollection
     */
    public function owners()
    {
        return $this->unique(function ($item) {
            return $item->owner_type.$item->owner_id;
        })->map(function ($item) {
            return $item->owner;
        });
    }

    /**
     * Filter this collection by owner.
     *
     * @param  Model  $owner
     * @return OrderItemCollection
     */
    public function whereOwner($owner)
    {
        return $this->filter(function ($item) use ($owner) {
            return (string) $item->owner_id === (string) $owner->getKey()
                && $item->owner_type === $owner->getMorphClass();
        });
    }

    /**
     * Returns a collection of OrderItemCollections, grouped by owner.
     *
     * @return BaseCollection
     */
    public function chunkByOwner()
    {
        return $this->owners()->sortBy(function ($owner) {
            return $owner->getMorphClass().'_'.$owner->getKey();
        })->mapWithKeys(function ($owner) {
            $key = $owner->getMorphClass().'_'.$owner->getKey();

            return [$key => $this->whereOwner($owner)];
        });
    }

    /**
     * Filter this collection by currency symbol.
     *
     * @return OrderItemCollection
     */
    public function whereCurrency($currency)
    {
        return $this->where('currency', $currency);
    }

    /**
     * Returns a collection of OrderItemCollections, grouped by currency symbol.
     *
     * @return BaseCollection
     */
    public function chunkByCurrency()
    {
        return $this->currencies()
            ->sort()
            ->mapWithKeys(function ($currency) {
                return [$currency => $this->whereCurrency($currency)];
            });
    }

    /**
     * Returns a collection of OrderItemCollections, grouped by currency symbol AND owner.
     *
     * @return BaseCollection
     */
    public function chunkByOwnerAndCurrency()
    {
        $result = collect();

        $this->chunkByOwner()->each(function ($owners_chunks, $owner_reference) use (&$result) {
            $owners_chunks->chunkByCurrency()->each(function ($chunk, $currency) use (&$result, $owner_reference) {
                $key = "{$owner_reference}_{$currency}";
                $result->put($key, $chunk);
            });
        });

        return $result;
    }

    /**
     * Preprocesses the OrderItems.
     *
     * @return OrderItemCollection
     */
    public function preprocess()
    {
        /** @var BaseCollection $items */
        $items = $this->flatMap(function (OrderItem $item) {
            return $item->preprocess();
        });

        return static::fromBaseCollection($items);
    }

    /**
     * Create an OrderItemCollection from a basic Collection.
     *
     * @return OrderItemCollection
     */
    public static function fromBaseCollection(BaseCollection $collection)
    {
        return new static($collection->all());
    }

    /**
     * Persist all items in the collection.
     *
     * @return BaseCollection|OrderItemCollection
     */
    public function save()
    {
        return $this->map(function (OrderItem $item) {
            $item->save();

            return $item;
        });
    }

    /**
     * Get a collection of distinct tax percentages in this collection.
     *
     * @return BaseCollection
     */
    public function taxPercentages()
    {
        return collect(array_values($this->pluck('tax_percentage')->unique()->sort()->all()));
    }

    /**
     * @throws LogicException
     */
    public function getTotal(): Money
    {
        if (count($this->currencies()) > 1) {
            throw new LogicException('Calculating the total requires items to be of the same currency.');
        }

        return new Money($this->sum('total'), new Currency($this->currency()));
    }

    public function currency(): string
    {
        $currencies = $this->currencies();

        if (count($currencies) > 1) {
            throw new LogicException(
                'Unable to retrieve a single currency as this collection contains multiple currencies.'
            );
        }

        if (empty($currencies)) {
            return strtoupper(Cashier::usesCurrency());
        }

        return strtoupper($currencies[0]);
    }
}
