<?php

namespace Laravel\Cashier\FirstPayment;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\MandateUpdated;
use Laravel\Cashier\FirstPayment\Actions\BaseAction;
use Laravel\Cashier\Order\OrderItemCollection;
use Mollie\Api\Resources\Payment as MolliePayment;

class FirstPaymentHandler
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $owner;

    /** @var \Mollie\Api\Resources\Payment */
    protected $molliePayment;

    /** @var \Illuminate\Support\Collection */
    protected $actions;

    /**
     * FirstPaymentHandler constructor.
     */
    public function __construct(MolliePayment $molliePayment)
    {
        $this->molliePayment = $molliePayment;
        $this->owner = $this->extractOwner();
        $this->actions = $this->extractActions();
    }

    /**
     * Execute all actions for the mandate payment and return the created Order.
     *
     * @return \Laravel\Cashier\Order\Order
     */
    public function execute()
    {
        $order = DB::transaction(function () {
            $this->owner->mollie_mandate_id = $this->molliePayment->mandateId;
            $this->owner->save();

            $orderItems = $this->executeActions();

            $order = Cashier::$orderModel::createProcessedFromItems($orderItems, [
                'mollie_payment_id' => $this->molliePayment->id,
                'mollie_payment_status' => $this->molliePayment->status,
            ]);

            // It's possible a payment from Cashier v1 is not yet tracked in the Cashier database.
            // In that case we create a record here.
            $localPayment = Cashier::$paymentModel::findByMolliePaymentOrCreate(
                $this->molliePayment,
                $this->owner,
                $this->actions->all()
            );

            $localPayment->update([
                'order_id' => $order->id,
                'mollie_payment_status' => $this->molliePayment->status,
                'mollie_mandate_id' => $this->molliePayment->mandateId,
            ]);

            return $order;
        });

        event(new MandateUpdated($this->owner, $this->molliePayment));

        return $order;
    }

    /**
     * Fetch the owner model using the mandate payment metadata.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function extractOwner()
    {
        $ownerType = $this->molliePayment->metadata->owner->type;
        $ownerID = $this->molliePayment->metadata->owner->id;

        $ownerClass = Relation::getMorphedModel($ownerType) ?? $ownerType;

        return $ownerClass::query()->findOrFail($ownerID);
    }

    /**
     * Build the action objects from the payment metadata.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function extractActions()
    {
        $payment = Cashier::$paymentModel::findByPaymentId($this->molliePayment->id);
        $actions = $payment?->first_payment_actions;

        /*
         * For legacy reasons, the first_payment_actions value is (de)serialized on the Payment model as an object
         * instead of an array. This is handled by `collect()` just fine.
         */
        return collect($actions)
            ->map(function ($action) {
                return $action->handler::createFromPayload(
                    object_to_array_recursive($action),
                    $this->owner
                );
            });
    }

    /**
     * Execute the Actions and return a collection of the resulting OrderItems.
     * These OrderItems are already paid for using the mandate payment.
     *
     * @return \Laravel\Cashier\Order\OrderItemCollection
     */
    protected function executeActions()
    {
        $orderItems = new OrderItemCollection;

        $this->actions->each(function (BaseAction $action) use (&$orderItems) {
            $orderItems = $orderItems->concat($action->execute());
        });

        return $orderItems;
    }

    /**
     * Retrieve the owner object.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Retrieve all Action objects.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getActions()
    {
        return $this->actions;
    }
}
