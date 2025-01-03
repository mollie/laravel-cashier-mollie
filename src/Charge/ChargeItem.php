<?php

namespace Laravel\Cashier\Charge;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\FirstPayment\Actions\AddGenericOrderItem;
use Laravel\Cashier\FirstPayment\Actions\BaseAction as FirstPaymentAction;
use Laravel\Cashier\Order\OrderItem;
use Money\Money;

class ChargeItem
{
    protected Model $owner;

    protected Money $unitPrice;

    protected string $description;

    protected int $quantity;

    protected float $taxPercentage;

    protected int $roundingMode;

    protected ?string $identifier;

    public function __construct(
        Model $owner,
        Money $unitPrice,
        string $description,
        int $quantity = 1,
        float $taxPercentage = 0,
        ?string $identifier = null,
        int $roundingMode = Money::ROUND_HALF_UP
    ) {
        $this->owner = $owner;
        $this->unitPrice = $unitPrice;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->taxPercentage = $taxPercentage;
        $this->roundingMode = $roundingMode;
        $this->identifier = $identifier;
    }

    public function toFirstPaymentAction(): FirstPaymentAction
    {
        $item = new AddGenericOrderItem(
            owner: $this->owner,
            unitPrice: $this->unitPrice,
            quantity: $this->quantity,
            description: $this->description,
            roundingMode: $this->roundingMode,
            identifier: $this->identifier
        );

        $item->withTaxPercentage($this->taxPercentage);

        return $item;
    }

    public function toOrderItem(array $overrides = []): OrderItem
    {
        return Cashier::$orderItemModel::make(array_merge([
            'owner_type' => $this->owner->getMorphClass(),
            'owner_id' => $this->owner->getKey(),
            'description' => $this->description,
            'quantity' => $this->quantity,
            'currency' => $this->unitPrice->getCurrency()->getCode(),
            'unit_price' => $this->unitPrice->getAmount(),
            'tax_percentage' => $this->taxPercentage,
            'process_at' => Carbon::now(),
        ], $overrides));
    }
}
