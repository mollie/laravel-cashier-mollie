<?php

namespace Laravel\Cashier\Charge;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Charge\Contracts\ChargeBuilder as Contract;
use Laravel\Cashier\FirstPayment\FirstPaymentBuilder;
use Laravel\Cashier\Http\RedirectToCheckoutResponse;

class FirstPaymentChargeBuilder implements Contract
{
    protected Model $owner;

    protected ChargeItemCollection $items;

    protected array $molliePaymentOverrides = [];

    protected string $redirectUrl;

    public function __construct(Model $owner)
    {
        $this->owner = $owner;
        $this->redirectUrl = url(config('cashier.first_payment.redirect_url', config('cashier.redirect_url')));
        $this->items = new ChargeItemCollection;
    }

    public function setItems(ChargeItemCollection $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function setRedirectUrl(string $redirectUrl)
    {
        $this->redirectUrl = url($redirectUrl);

        return $this;
    }

    public function addItem(ChargeItem $item): self
    {
        $this->items->add($item);

        return $this;
    }

    public function molliePaymentOverrides(array $overrides): self
    {
        $this->molliePaymentOverrides = $overrides;

        return $this;
    }

    public function create()
    {
        if ($this->items->isEmpty()) {
            throw new \LogicException('Charge item list cannot be empty');
        }

        $firstPaymentBuilder = new FirstPaymentBuilder($this->owner, $this->molliePaymentOverrides);

        $molliePayment = $firstPaymentBuilder
            ->inOrderTo($this->items->toFirstPaymentActionCollection()->all())
            ->setRedirectUrl($this->redirectUrl)
            ->create();

        return RedirectToCheckoutResponse::forPayment($molliePayment);
    }
}
