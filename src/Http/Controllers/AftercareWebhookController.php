<?php

namespace Laravel\Cashier\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\ChargebackReceived;
use Laravel\Cashier\Order\Order;
use Laravel\Cashier\Refunds\Refund;
use Mollie\Api\Resources\Payment as MolliePayment;
use Mollie\Api\Resources\Refund as MollieRefund;
use Mollie\Api\Types\RefundStatus;
use Money\Currency;
use Money\Money;
use Symfony\Component\HttpFoundation\Response;

class AftercareWebhookController extends BaseWebhookController
{
    /**
     * @param  Request  $request
     * @return Response
     *
     * @throws \Mollie\Api\Exceptions\ApiException Only in debug mode
     */
    public function handleWebhook(Request $request)
    {
        $molliePayment = $this->getMolliePaymentById($request->get('id'));

        if ($molliePayment && $molliePayment->hasRefunds()) {
            /** @var Order|null $order */
            $order = Cashier::$orderModel::findByMolliePaymentId($molliePayment->id);

            if (!$order) {
                return new Response(null, config('app.debug') ? 404 : 200);
            }

            $this->handleRefunds($order, $molliePayment);
        }

        if ($molliePayment && $molliePayment->hasChargebacks()) {
            /** @var \Laravel\Cashier\Payment|null $localPayment */
            $localPayment = Cashier::$paymentModel::findByPaymentId($molliePayment->id);

            if (!$localPayment) {
                return new Response(null, config('app.debug') ? 404 : 200);
            }

            $molliePaymentAmountChargedBackTotal = mollie_object_to_money($molliePayment->amountChargedBack);
            $locallyKnownAmountChargedBack = $localPayment->getAmountChargedBack();

            if ($locallyKnownAmountChargedBack->lessThan($molliePaymentAmountChargedBackTotal)) {
                $localPayment->amount_charged_back = (int) $molliePaymentAmountChargedBackTotal->getAmount();
                $localPayment->save();

                $amountChargedBackNow = $molliePaymentAmountChargedBackTotal->subtract(
                    $locallyKnownAmountChargedBack
                );

                Event::dispatch(
                    new ChargebackReceived($localPayment, $amountChargedBackNow)
                );
            }
        }

        return new Response(null, 200);
    }

    protected function handleRefunds(Order $order, MolliePayment $molliePayment)
    {
        /** @var \Laravel\Cashier\Refunds\RefundCollection $localRefunds */
        $localRefunds = $order->refunds()->whereUnprocessed()->get();
        $mollieRefunds = collect($molliePayment->refunds());

        $paymentAmountRefundedHasChanged = false;
        $localRefunds->each(function (Refund $localRefund) use ($mollieRefunds, &$paymentAmountRefundedHasChanged) {
            $mollieRefund = $this->extractMatchingMollieRefundForLocalRefund($localRefund, $mollieRefunds);

            if ($mollieRefund) {
                if ($mollieRefund->isTransferred() && $localRefund->mollie_refund_status !== RefundStatus::STATUS_REFUNDED) {
                    $localRefund->handleProcessed();
                    $paymentAmountRefundedHasChanged = true;
                } elseif ($mollieRefund->isFailed() && $localRefund->mollie_refund_status !== RefundStatus::STATUS_FAILED) {
                    $localRefund->handleFailed();
                    $paymentAmountRefundedHasChanged = true;
                }
            }
        });

        if ($paymentAmountRefundedHasChanged) {
            // Update the locally known refunded amount
            $amountRefunded = $molliePayment->amountRefunded
                ? mollie_object_to_money($molliePayment->amountRefunded)
                : new Money(0, new Currency($molliePayment->amount->currency));

            $localPayment = Cashier::$paymentModel::findByPaymentId($molliePayment->id);
            $localPayment->update(['amount_refunded' => (int) $amountRefunded->getAmount()]);
        }
    }

    /**
     * @param  \Laravel\Cashier\Refunds\Refund  $localRefund
     * @param  \Illuminate\Support\Collection  $mollieRefunds
     * @return MollieRefund|null
     */
    protected function extractMatchingMollieRefundForLocalRefund(Refund $localRefund, Collection $mollieRefunds)
    {
        return $mollieRefunds->first(function (MollieRefund $mollieRefund) use ($localRefund) {
            return $mollieRefund->id === $localRefund->mollie_refund_id;
        });
    }
}
