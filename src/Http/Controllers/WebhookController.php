<?php

namespace Laravel\Cashier\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Laravel\Cashier\Mollie\Contracts\UpdateMolliePayment;
use Laravel\Cashier\Payment as CashierPayment;
use Mollie\Api\Resources\Payment;
use Mollie\Api\Types\PaymentStatus;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends BaseWebhookController
{
    /**
     * @param  Request  $request
     * @return Response
     *
     * @throws \Mollie\Api\Exceptions\ApiException Only in debug mode
     */
    public function handleWebhook(Request $request)
    {
        $payment = CashierPayment::with('owner')->firstWhere('mollie_payment_id', $request->get('id'));

        $molliePayment = $this->getMolliePaymentById(
            $request->get('id'),
            owner: $payment->owner instanceof ProvidesOauthToken ? $payment->owner : null,
        );

        if ($molliePayment) {
            $order = $this->getOrder($molliePayment);

            if ($order && $order->mollie_payment_status !== $molliePayment->status) {
                switch ($molliePayment->status) {
                    case PaymentStatus::STATUS_PAID:
                        $order->handlePaymentPaid($molliePayment);
                        $molliePayment->webhookUrl = route('webhooks.mollie.aftercare');

                        /** @var UpdateMolliePayment $updateMolliePayment */
                        $updateMolliePayment = app()->make(UpdateMolliePayment::class);
                        $updateMolliePayment->execute($molliePayment, $payment->owner instanceof ProvidesOauthToken ? $payment->owner : null);

                        break;
                    case PaymentStatus::STATUS_FAILED:
                        $order->handlePaymentFailed($molliePayment);

                        break;
                    default:
                        break;
                }
            }
        }

        return new Response(null, 200);
    }

    /**
     * @param  \Mollie\Api\Resources\Payment  $payment
     * @return \Laravel\Cashier\Order\Order|null
     */
    protected function getOrder(Payment $payment)
    {
        $order = Cashier::$orderModel::findByMolliePaymentId($payment->id);

        if (! $order && isset($payment->metadata, $payment->metadata->temporary_mollie_payment_id)) {
            $order = Cashier::$orderModel::findByMolliePaymentId($payment->metadata->temporary_mollie_payment_id);

            if ($order) {
                // Store the definite payment id.
                $order->update(['mollie_payment_id' => $payment->id]);
            }
        }

        return $order;
    }
}
