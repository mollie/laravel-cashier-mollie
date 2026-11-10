<?php

namespace Laravel\Cashier\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait HasOwner
{
    /**
     * Retrieve the model's owner.
     *
     * @return MorphTo
     */
    public function owner()
    {
        return $this->morphTo('owner');
    }

    /**
     * Scope a query to only records for a specific owner.
     *
     * @param  Builder  $query
     * @param  mixed  $owner
     * @return Builder
     */
    public function scopeWhereOwner($query, $owner)
    {
        return $query
            ->where('owner_id', $owner->getKey())
            ->where('owner_type', $owner->getMorphClass());
    }
}
