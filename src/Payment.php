<?php

namespace Laravel\Cashier;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;
use Laravel\Cashier\Mollie\Contracts\UpdateMolliePayment;
use Laravel\Cashier\Order\ConvertsToMoney;
use Laravel\Cashier\Traits\HasOwner;
use Mollie\Api\Resources\Payment as MolliePayment;
use Mollie\Api\Types\PaymentStatus;
use Money\Currency;
use Money\Money;

/**
 * @property string mollie_payment_id
 * @property string mollie_payment_status
 * @property string owner_type
 * @property int owner_id
 * @property Model owner
 * @property int order_id
 * @property string status
 * @property string currency
 * @property int amount
 * @property int amount_refunded
 * @property int amount_charged_back
 * @property object first_payment_actions // An object for legacy reasons
 * @property string mollie_mandate_id
 * @property \Laravel\Cashier\Order\Order order
 * @property array|null metadata
 *
 * @method static create(array $data)
 * @method static make(array $data)
 */
class Payment extends Model
{
    use ConvertsToMoney;
    use HasOwner;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string[]
     */
    protected $casts = [
        'first_payment_actions' => 'object',
        'metadata' => 'array',
    ];

    /**
     * @return static
     */
    public static function createFromMolliePayment(MolliePayment $payment, Model $owner, array $actions = [], array $overrides = []): self
    {
        return tap(static::makeFromMolliePayment($payment, $owner, $actions, $overrides))->save();
    }

    /**
     * @return static
     */
    public static function makeFromMolliePayment(MolliePayment $payment, Model $owner, array $actions = [], array $overrides = []): self
    {
        $amountChargedBack = $payment->amountChargedBack
            ? mollie_object_to_money($payment->amountChargedBack)
            : new Money(0, new Currency($payment->amount->currency));

        $amountRefunded = $payment->amountRefunded
            ? mollie_object_to_money($payment->amountRefunded)
            : new Money(0, new Currency($payment->amount->currency));

        $localActions = ! empty($actions) ? $actions : $payment->metadata->actions ?? null;

        return static::make(array_merge([
            'mollie_payment_id' => $payment->id,
            'mollie_payment_status' => $payment->status,
            'owner_type' => $owner->getMorphClass(),
            'owner_id' => $owner->getKey(),
            'currency' => $payment->amount->currency,
            'amount' => (int) mollie_object_to_money($payment->amount)->getAmount(),
            'amount_refunded' => (int) $amountRefunded->getAmount(),
            'amount_charged_back' => (int) $amountChargedBack->getAmount(),
            'mollie_mandate_id' => $payment->mandateId,
            'first_payment_actions' => $localActions,
        ], $overrides));
    }

    /**
     * Retrieve an Order by the Mollie Payment id.
     *
     * @return static
     */
    public static function findByPaymentId($id): ?self
    {
        return static::where('mollie_payment_id', $id)->first();
    }

    /**
     * Retrieve a Payment by the Mollie Payment id or throw an Exception if not found.
     *
     * @return static
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function findByPaymentIdOrFail($id): self
    {
        return static::where('mollie_payment_id', $id)->firstOrFail();
    }

    /**
     * Find a Payment by the Mollie payment id, or create a new Payment record from a Mollie payment if not found.
     *
     * @return static
     */
    public static function findByMolliePaymentOrCreate(MolliePayment $molliePayment, Model $owner, array $actions = []): self
    {
        $payment = static::findByPaymentId($molliePayment->id);

        if ($payment) {
            return $payment;
        }

        $newPayment = static::createFromMolliePayment($molliePayment, $owner, $actions);

        if ($newPayment->mollie_payment_status === PaymentStatus::STATUS_PAID) {
            $molliePayment->webhookUrl = route('webhooks.mollie.aftercare');

            /** @var UpdateMolliePayment $updateMolliePayment */
            $updateMolliePayment = app()->make(UpdateMolliePayment::class);
            $updateMolliePayment->execute($molliePayment);
        }

        return $newPayment;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Cashier::$orderModel);
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmount(): Money
    {
        return $this->toMoney($this->amount);
    }

    public function getAmountRefunded(): Money
    {
        return $this->toMoney($this->amount_refunded);
    }

    public function getAmountChargedBack(): Money
    {
        return $this->toMoney($this->amount_charged_back);
    }

    /**
     * Fetch the Mollie payment resource for this local payment instance.
     */
    public function asMolliePayment(): MolliePayment
    {
        return app()->make(GetMolliePayment::class)->execute($this->mollie_payment_id);
    }
}
