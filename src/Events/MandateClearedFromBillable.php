<?php

namespace Laravel\Cashier\Events;

class MandateClearedFromBillable extends BaseEvent
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $owner;

    /**
     * @var string
     */
    public $oldMandateId;

    /**
     * ClearedMandate constructor.
     *
     * @param  mixed  $owner
     */
    public function __construct($owner, string $oldMandateId)
    {
        $this->owner = $owner;
        $this->oldMandateId = $oldMandateId;
    }
}
