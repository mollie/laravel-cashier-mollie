<?php

namespace Laravel\Cashier\FirstPayment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\MandateUpdated;
use Laravel\Cashier\FirstPayment\Actions\BaseAction;
use Laravel\Cashier\Order\Order;
use Laravel\Cashier\Order\OrderItemCollection;
use Mollie\Api\Resources\Payment;
use Mollie\Api\Resources\Payment as MolliePayment;

class FirstPaymentHandler
{
    /** @var Model */
    protected $owner;

    /** @var Payment */
    protected $molliePayment;

    /** @var Collection */
    protected $actions;

    /** @var bool */
    protected $alreadyProcessed = false;

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
     * @return Order
     */
    public function execute()
    {
        $this->alreadyProcessed = false;

        $order = DB::transaction(function () {
            $localPayment = Cashier::$paymentModel::query()
                ->where('mollie_payment_id', $this->molliePayment->id)
                ->lockForUpdate()
                ->first();

            if ($localPayment?->order) {
                $this->alreadyProcessed = true;

                return $localPayment->order;
            }

            $this->owner->mollie_mandate_id = $this->molliePayment->mandateId;
            $this->owner->save();

            $orderItems = $this->executeActions();

            $order = Cashier::$orderModel::createProcessedFromItems($orderItems, [
                'mollie_payment_id' => $this->molliePayment->id,
                'mollie_payment_status' => mollie_enum_value($this->molliePayment->status),
            ]);

            // It's possible a payment from Cashier v1 is not yet tracked in the Cashier database.
            // In that case we create a record here.
            $localPayment = $localPayment ?: Cashier::$paymentModel::findByMolliePaymentOrCreate(
                $this->molliePayment,
                $this->owner,
                $this->actions->all()
            );

            $localPayment->update([
                'order_id' => $order->id,
                'mollie_payment_status' => mollie_enum_value($this->molliePayment->status),
                'mollie_mandate_id' => $this->molliePayment->mandateId,
            ]);

            return $order;
        });

        if (! $this->alreadyProcessed) {
            event(new MandateUpdated($this->owner, $this->molliePayment));
        }

        return $order;
    }

    /**
     * Determine if execute() returned an already processed first payment.
     *
     * @return bool
     */
    public function wasAlreadyProcessed()
    {
        return $this->alreadyProcessed;
    }

    /**
     * Fetch the owner model using the mandate payment metadata.
     *
     * @return Model
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
     * @return Collection
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
     * @return OrderItemCollection
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
     * @return Model
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Retrieve all Action objects.
     *
     * @return Collection
     */
    public function getActions()
    {
        return $this->actions;
    }
}
