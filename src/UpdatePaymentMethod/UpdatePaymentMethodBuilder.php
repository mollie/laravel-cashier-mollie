<?php

namespace Laravel\Cashier\UpdatePaymentMethod;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\FirstPayment\Actions\AddBalance;
use Laravel\Cashier\FirstPayment\Actions\AddGenericOrderItem;
use Laravel\Cashier\FirstPayment\FirstPaymentBuilder;
use Laravel\Cashier\Http\RedirectToCheckoutResponse;
use Laravel\Cashier\Plan\Contracts\PlanRepository;
use Laravel\Cashier\Traits\HandlesMoneyRounding;
use Laravel\Cashier\UpdatePaymentMethod\Contracts\UpdatePaymentMethodBuilder as Contract;
use Money\Money;

class UpdatePaymentMethodBuilder implements Contract
{
    use HandlesMoneyRounding;

    /**
     * The billable model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $owner;

    /**
     * @var bool
     */
    protected $skipBalance = false;

    /**
     * UpdatePaymentMethodBuilder constructor.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $owner
     */
    public function __construct(Model $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        $payment = (new FirstPaymentBuilder($this->owner))
            ->setRedirectUrl(config('cashier.update_payment_method.redirect_url'))
            ->setFirstPaymentMethod($this->allowedPaymentMethods())
            ->inOrderTo($this->getPaymentActions())
            ->create();

        $payment->update();

        return RedirectToCheckoutResponse::forPayment($payment);
    }

    /**
     * @return $this
     */
    public function skipBalance()
    {
        $this->skipBalance = true;

        return $this;
    }

    /**
     * @return array
     */
    protected function allowedPaymentMethods()
    {
        $paymentMethods = $this->owner->subscriptions->map(function ($subscription) {
            if ($subscription->active()) {
                $planModel = app(PlanRepository::class)::findOrFail($subscription->plan);

                return $planModel->firstPaymentMethod();
            }
        })->filter()->unique()->collapse();

        return $paymentMethods->all();
    }

    /**
     * @return \Laravel\Cashier\FirstPayment\Actions\AddBalance[]|\Laravel\Cashier\FirstPayment\Actions\AddGenericOrderItem[]
     */
    protected function getPaymentActions()
    {
        if ($this->skipBalance) {
            return [$this->addGenericItemAction()];
        }

        return [$this->addToBalanceAction()];
    }

    /**
     * @return \Laravel\Cashier\FirstPayment\Actions\AddBalance
     */
    protected function addToBalanceAction()
    {
        return
            new AddBalance(
                $this->owner,
                mollie_array_to_money(config('cashier.update_payment_method.amount')),
                1,
                config('cashier.update_payment_method.description')
            );
    }

    /**
     * @return \Laravel\Cashier\FirstPayment\Actions\AddGenericOrderItem
     */
    protected function addGenericItemAction()
    {
        $total = mollie_array_to_money(config('cashier.update_payment_method.amount'));
        $taxPercentage = $this->owner->taxPercentage() * 0.01;

        $subtotal = $this->subtotalForTotalIncludingTax($total, $taxPercentage);

        return new AddGenericOrderItem($this->owner, $subtotal, 1, config('cashier.update_payment_method.description'));
    }

    /**
     * @param  \Money\Money  $total
     * @param  float  $taxPercentage
     * @return \Money\Money
     */
    protected function subtotalForTotalIncludingTax(Money $total, float $taxPercentage)
    {
        $vat = $total->divide(
            sprintf('%.8F', 1 + $taxPercentage)
        )->multiply(
            sprintf('%.8F', $taxPercentage),
            $this->roundingMode($total, $taxPercentage)
        );

        return $total->subtract($vat);
    }
}
