<?php

namespace Laravel\Cashier\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\BalanceTurnedStale;
use Laravel\Cashier\Events\OrderCreated;
use Laravel\Cashier\Events\OrderPaymentFailed;
use Laravel\Cashier\Events\OrderPaymentFailedDueToInvalidMandate;
use Laravel\Cashier\Events\OrderPaymentPaid;
use Laravel\Cashier\Events\OrderProcessed;
use Laravel\Cashier\Exceptions\AmountExceedsMolliePaymentMethodLimit;
use Laravel\Cashier\Exceptions\InvalidMandateException;
use Laravel\Cashier\Exceptions\OrderRetryRequiresStatusFailedException;
use Laravel\Cashier\MandatedPayment\MandatedPaymentBuilder;
use Laravel\Cashier\Order\Contracts\MaximumPayment;
use Laravel\Cashier\Order\Contracts\MinimumPayment;
use Laravel\Cashier\Refunds\RefundBuilder;
use Laravel\Cashier\Traits\HasOwner;
use LogicException;
use Mollie\Api\Resources\Mandate;
use Mollie\Api\Resources\Payment as MolliePayment;
use Mollie\Api\Types\PaymentStatus;

/**
 * @property int id
 * @property string owner_type
 * @property int owner_id
 * @property string number
 * @property string currency
 * @property int subtotal
 * @property int tax
 * @property int total
 * @property int balance_before
 * @property int credit_used
 * @property int total_due
 * @property string mollie_payment_id
 * @property string mollie_payment_status
 * @property \Carbon\Carbon|null processed_at
 * @property int amount_refunded
 * @property int amount_charged_back
 * @property \Laravel\Cashier\Order\OrderItemCollection items
 * @property \Laravel\Cashier\Refunds\RefundCollection refunds
 *
 * @method static create(array $data)
 */
class Order extends Model
{
    use HasOwner;
    use ConvertsToMoney;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount_refunded' => 'int',
        'amount_charged_back' => 'int',
        'tax' => 'int',
        'subtotal' => 'int',
        'total' => 'int',
        'balance_before' => 'int',
        'credit_used' => 'int',
        'total_due' => 'int',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'processed_at',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    /**
     * @return int
     */
    public function getBalanceAfterAttribute()
    {
        return (int) $this->getBalanceBefore()->subtract($this->getCreditUsed())->getAmount();
    }

    /**
     * Creates an order from a collection of OrderItems
     *
     * @param  \Laravel\Cashier\Order\OrderItemCollection  $items
     * @param  array  $overrides
     * @param  bool  $process_items
     * @return Order
     */
    public static function createFromItems(OrderItemCollection $items, $overrides = [], $process_items = true)
    {
        return DB::transaction(function () use ($items, $overrides, $process_items) {
            if ($process_items) {
                $items = $items->preprocess();
            }

            if ($items->currencies()->count() > 1) {
                throw new LogicException('Creating an order requires items to have a single currency.');
            }

            if ($items->owners()->count() > 1) {
                throw new LogicException('Creating an order requires items to have a single owner.');
            }

            $currency = $items->first()->currency;
            $owner = $items->first()->owner;

            $total = $items->sum('total');

            $order = static::create(array_merge([
                'owner_id' => $owner->getKey(),
                'owner_type' => $owner->getMorphClass(),
                'number' => static::numberGenerator()->generate(),
                'currency' => $currency,
                'subtotal' => $items->sum('subtotal'),
                'tax' => $items->sum('tax'),
                'total' => $total,
                'total_due' => $total,
            ], $overrides));

            $items->each(function (OrderItem $item) use ($order, $process_items) {
                $item->update(['order_id' => $order->id]);

                if ($process_items) {
                    $item->process();
                }
            });

            Event::dispatch(new OrderCreated($order));

            return $order;
        });
    }

    /**
     * Creates a processed order from a collection of OrderItems
     *
     * @param  \Laravel\Cashier\Order\OrderItemCollection  $items
     * @param  array  $overrides
     * @return Order
     */
    public static function createProcessedFromItems(OrderItemCollection $items, $overrides = [])
    {
        $order = static::createFromItems(
            $items,
            array_merge([
                'processed_at' => now(),
            ], $overrides),
            false
        );

        Event::dispatch(new OrderProcessed($order));

        return $order;
    }

    /**
     * @param $item
     * @param  array  $overrides
     * @return \Laravel\Cashier\Order\Order
     */
    public static function createProcessedFromItem($item, $overrides = [])
    {
        return static::createProcessedFromItems(new OrderItemCollection([$item]), $overrides);
    }

    /**
     * Processes the Order into Credit, Refund or Mollie Payment - whichever is appropriate.
     *
     * @return $this
     *
     * @throws \Laravel\Cashier\Exceptions\InvalidMandateException
     */
    public function processPayment()
    {
        $this->update(['mollie_payment_id' => 'temp_'.Str::uuid()]);

        DB::transaction(function () {
            $owner = $this->owner;

            // Process user balance, if any
            if ($this->getTotal()->getAmount() > 0 && $owner->hasCredit($this->currency)) {
                $total = $this->getTotal();
                $this->balance_before = $owner->credit($this->currency)->value;

                $creditUsed = $owner->maxOutCredit($total);
                $this->credit_used = (int) $creditUsed->getAmount();
                $this->total_due = $total->subtract($creditUsed)->getAmount();
            }

            try {
                $minimumPaymentAmount = $this->ensureValidMandateAndMinimumPaymentAmountWhenTotalDuePositive();
                $maximumPaymentAmount = $this->ensureValidMandateAndMaximumPaymentAmountWhenTotalDuePositive();
            } catch (InvalidMandateException $e) {
                return $this->handlePaymentFailedDueToInvalidMandate();
            }

            $totalDue = money($this->total_due, $this->currency);

            if ($totalDue->greaterThan($maximumPaymentAmount)) {
                $this->items->each(function (OrderItem $item) {
                    $item->update(['order_id' => null]);
                });
                $this->delete();

                throw new AmountExceedsMolliePaymentMethodLimit();
            }

            switch (true) {
                case $totalDue->isZero():
                    // No payment processing required
                    $this->mollie_payment_id = null;

                    break;

                case $totalDue->lessThan($minimumPaymentAmount):
                    // No payment processing required
                    $this->mollie_payment_id = null;

                    // Add credit to the owner's balance
                    $credit = Cashier::$creditModel::addAmountForOwner($owner, money(-($this->total_due), $this->currency));

                    if (! $owner->hasActiveSubscriptionWithCurrency($this->currency)) {
                        Event::dispatch(new BalanceTurnedStale($credit));
                    }

                    break;

                case $totalDue->greaterThanOrEqual($minimumPaymentAmount):

                    // Create Mollie payment
                    $payment = (new MandatedPaymentBuilder(
                        $owner,
                        'Order '.$this->number,
                        $totalDue,
                        url(config('cashier.webhook_url')),
                        [
                            'metadata' => [
                                'temporary_mollie_payment_id' => $this->mollie_payment_id,
                            ],
                        ]
                    ))->create();

                    $this->mollie_payment_id = $payment->id;
                    $this->mollie_payment_status = 'open';

                    break;

                default:
                    break;
            }

            $this->processed_at = now();
            $this->save();
        });

        Event::dispatch(new OrderProcessed($this));

        return $this;
    }

    /**
     * The order's items.
     *
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(Cashier::$orderItemModel);
    }

    /**
     * The refunds for this order.
     *
     * @return HasMany
     */
    public function refunds()
    {
        return $this->hasMany(Cashier::$refundModel, 'original_order_id', 'id');
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new OrderCollection($models);
    }

    /**
     * Get the invoice for this Order.
     *
     * @param  null  $id
     * @param  null  $date
     * @return \Laravel\Cashier\Order\Invoice
     */
    public function invoice($id = null, $date = null)
    {
        $invoice = (new Invoice(
            $this->currency,
            $id ?: $this->number,
            $date ?: $this->created_at
        ))->addItems($this->items)
            ->setStartingBalance($this->getBalanceBefore())
            ->setCompletedBalance($this->getBalanceAfter())
            ->setUsedBalance($this->getCreditUsed());

        $invoice->setReceiverAddress($this->owner->getInvoiceInformation());

        $extra_information = null;
        $owner = $this->owner;

        if (method_exists($owner, 'getExtraBillingInformation')) {
            $extra_information = $owner->getExtraBillingInformation();

            if (! empty($extra_information)) {
                $extra_information = explode("\n", $extra_information);

                if (is_array($extra_information) && ! empty($extra_information)) {
                    $invoice->setExtraInformation($extra_information);
                }
            }
        }

        return $invoice;
    }

    /**
     * Checks whether the order is processed.
     *
     * @return bool
     */
    public function isProcessed()
    {
        return ! empty($this->processed_at);
    }

    /**
     * Scope the query to only include processed orders.
     *
     * @param $query
     * @param  bool  $processed
     * @return Builder
     */
    public function scopeProcessed($query, $processed = true)
    {
        if ($processed) {
            return $query->whereNotNull('processed_at');
        }

        return $query->whereNull('processed_at');
    }

    /**
     * Scope the query to only include unprocessed orders.
     *
     * @param $query
     * @param  bool  $unprocessed
     * @return Builder
     */
    public function scopeUnprocessed($query, $unprocessed = true)
    {
        return $query->processed(! $unprocessed);
    }

    /**
     * Scope the query to only include orders with a specific Mollie payment status.
     *
     * @param $query
     * @param  string  $status
     * @return Builder
     */
    public function scopePaymentStatus($query, $status)
    {
        return $query->where('mollie_payment_status', $status);
    }

    /**
     * Scope the query to only include paid orders.
     *
     * @param $query
     * @return Builder
     */
    public function scopePaid($query)
    {
        return $this
            ->scopePaymentStatus($query, PaymentStatus::STATUS_PAID)
            ->orWhere('total_due', '=', 0);
    }

    /**
     * Retrieve an Order by the Mollie Payment id.
     *
     * @param $id
     * @return ?static
     */
    public static function findByMolliePaymentId($id)
    {
        return static::where('mollie_payment_id', $id)->first();
    }

    /**
     * Retrieve an Order by the Mollie Payment id or throw an Exception if not found.
     *
     * @param $id
     * @return static
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function findByMolliePaymentIdOrFail($id)
    {
        return static::where('mollie_payment_id', $id)->firstOrFail();
    }

    /**
     * Checks whether credit was used in the Order.
     * The credit applied will be reset to 0 when an Order payment fails.
     *
     * @return bool
     */
    public function creditApplied()
    {
        return $this->credit_used != 0;
    }

    /**
     * Handles a failed payment for the Order.
     * Restores any credit used to the customer's balance and resets the credits applied to the Order.
     * Invokes handlePaymentFailed() on each related OrderItem.
     *
     * @param  \Mollie\Api\Resources\Payment  $molliePayment
     * @return $this
     */
    public function handlePaymentFailed(MolliePayment $molliePayment)
    {
        return DB::transaction(function () use ($molliePayment) {
            if ($this->creditApplied()) {
                $this->owner->addCredit($this->getCreditUsed());
            }

            $this->update([
                'mollie_payment_status' => 'failed',
                'balance_before' => 0,
                'credit_used' => 0,
            ]);

            // It's possible a payment from Cashier v1 is not yet tracked in the Cashier database.
            // In that case we create a record here.
            $localPayment = Cashier::$paymentModel::findByMolliePaymentOrCreate($molliePayment, $this->owner);
            $localPayment->update([
                'mollie_payment_status' => 'failed',
            ]);

            Event::dispatch(new OrderPaymentFailed($this));

            $this->items->each(function (OrderItem $item) {
                $item->handlePaymentFailed();
            });

            $this->owner->validateMollieMandate();

            return $this;
        });
    }

    /**
     * Handles a failed payment for the Order due to an invalid Mollie payment Mandate.
     * Restores any credit used to the customer's balance and resets the credits applied to the Order.
     * Invokes handlePaymentFailed() on each related OrderItem.
     *
     * @return $this
     */
    public function handlePaymentFailedDueToInvalidMandate()
    {
        return DB::transaction(function () {
            if ($this->creditApplied()) {
                $this->owner->addCredit($this->getCreditUsed());
            }

            $this->update([
                'mollie_payment_id' => null,
                'mollie_payment_status' => 'failed',
                'balance_before' => 0,
                'credit_used' => 0,
                'processed_at' => now(),
            ]);

            Event::dispatch(new OrderPaymentFailedDueToInvalidMandate($this));

            $this->items->each(function (OrderItem $item) {
                $item->handlePaymentFailed();
            });

            $this->owner->clearMollieMandate();

            return $this;
        });
    }

    /**
     * Handles a paid payment for this order.
     * Invokes handlePaymentPaid() on each related OrderItem.
     *
     * @param  \Mollie\Api\Resources\Payment  $molliePayment
     * @return $this
     */
    public function handlePaymentPaid(MolliePayment $molliePayment)
    {
        return DB::transaction(function () use ($molliePayment) {
            $this->update(['mollie_payment_status' => 'paid']);

            // It's possible a payment from Cashier v1 is not yet tracked in the Cashier database.
            // In that case we create a record here.
            $localPayment = Cashier::$paymentModel::findByMolliePaymentOrCreate($molliePayment, $this->owner);
            $localPayment->update([
                'mollie_payment_status' => 'paid',
                'order_id' => $this->id,
            ]);

            Event::dispatch(new OrderPaymentPaid($this));

            $this->items->each(function (OrderItem $item) {
                $item->handlePaymentPaid();
            });

            return $this;
        });
    }

    /**
     * @return \Money\Money
     */
    public function getSubtotal()
    {
        return $this->toMoney($this->subtotal);
    }

    /**
     * @return \Money\Money
     */
    public function getTax()
    {
        return $this->toMoney($this->tax);
    }

    /**
     * @return \Money\Money
     */
    public function getTotal()
    {
        return $this->toMoney($this->total);
    }

    /**
     * @return \Money\Money
     */
    public function getTotalDue()
    {
        return $this->toMoney($this->total_due);
    }

    /**
     * @return \Money\Money
     */
    public function getBalanceBefore()
    {
        return $this->toMoney($this->balance_before);
    }

    /**
     * @return \Money\Money
     */
    public function getBalanceAfter()
    {
        return $this->toMoney($this->balance_after);
    }

    /**
     * @return \Money\Money
     */
    public function getCreditUsed()
    {
        return $this->toMoney($this->credit_used);
    }

    /**
     * @return \Money\Money
     */
    public function getAmountRefunded()
    {
        return $this->toMoney($this->amount_refunded);
    }

    /**
     * @return \Money\Money
     */
    public function getAmountChargedBack()
    {
        return $this->toMoney($this->amount_charged_back);
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get an empty refund builder for this order.
     *
     * @return \Laravel\Cashier\Refunds\RefundBuilder
     */
    public function refundBuilder()
    {
        return RefundBuilder::forOrder($this);
    }

    /**
     * Initiate a new refund for this order.
     *
     * @return \Laravel\Cashier\Refunds\RefundBuilder
     */
    public function newRefund()
    {
        return $this->refundBuilder();
    }

    /**
     * Get a refund builder prepared to completely refund this order.
     *
     * @return \Laravel\Cashier\Refunds\RefundBuilder
     */
    public function completeRefundBuilder()
    {
        return RefundBuilder::forWholeOrder($this);
    }

    /**
     * Initiate a complete refund for this order.
     *
     * @return \Laravel\Cashier\Refunds\Refund
     */
    public function refundCompletely()
    {
        return $this->completeRefundBuilder()->create();
    }

    /**
     * @param  \Mollie\Api\Resources\Mandate  $mandate
     *
     * @throws \Laravel\Cashier\Exceptions\InvalidMandateException
     */
    protected function guardMandate(?Mandate $mandate)
    {
        if (empty($mandate) || ! $mandate->isValid()) {
            throw new InvalidMandateException('Cannot process payment without valid mandate for order id '.$this->id);
        }
    }

    /**
     * @return \Laravel\Cashier\Order\OrderNumberGenerator
     */
    protected static function numberGenerator()
    {
        return app()->make(config('cashier.order_number_generator.model'));
    }

    /**
     * @return \Money\Money
     *
     * @throws InvalidMandateException
     */
    private function ensureValidMandateAndMinimumPaymentAmountWhenTotalDuePositive(): \Money\Money
    {
        // If the total due amount is below 0 checking for a mandate doesn't make sense.
        if ((int) $this->getTotalDue()->getAmount() > 0) {
            $mandate = $this->owner->mollieMandate();
            $this->guardMandate($mandate);
            $minimumPaymentAmount = app(MinimumPayment::class)::forMollieMandate($mandate, $this->getCurrency());
        } else {
            $minimumPaymentAmount = money(0, $this->getCurrency());
        }

        return $minimumPaymentAmount;
    }

    /**
     * @return \Money\Money
     *
     * @throws InvalidMandateException
     */
    private function ensureValidMandateAndMaximumPaymentAmountWhenTotalDuePositive(): \Money\Money
    {
        if ((int) $this->getTotalDue()->getAmount() > 0) {
            $mandate = $this->owner->mollieMandate();
            $this->guardMandate($mandate);

            $maximumPaymentAmount = app(MaximumPayment::class)::forMollieMandate($mandate, $this->getCurrency());
        } else {
            $maximumPaymentAmount = money(0, $this->getCurrency());
        }

        return $maximumPaymentAmount;
    }

    /**
     * The payments for this order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Cashier::$paymentModel)->orderByDesc('updated_at');
    }

    /**
     * @throws \Laravel\Cashier\Exceptions\InvalidMandateException
     * @throws \Laravel\Cashier\Exceptions\OrderRetryRequiresStatusFailedException
     */
    public function retryNow()
    {
        if ($this->mollie_payment_status != 'failed') {
            throw new OrderRetryRequiresStatusFailedException();
        }

        if (! $this->owner->validMollieMandate()) {
            throw new InvalidMandateException();
        }

        return DB::transaction(function () {
            $this->processed_at = null;
            $this->mollie_payment_id = null;
            $this->mollie_payment_status = null;
            $this->save();

            $this->processPayment();
        });
    }
}
