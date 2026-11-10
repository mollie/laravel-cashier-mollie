<?php

namespace Laravel\Cashier\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class MandateClearedFromBillable
{
    use SerializesModels;

    /**
     * @var Model
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
