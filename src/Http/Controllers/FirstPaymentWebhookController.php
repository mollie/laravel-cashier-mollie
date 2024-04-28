<?php

namespace Laravel\Cashier\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Contracts\ProvidesOauthToken;
use Laravel\Cashier\Events\FirstPaymentFailed;
use Laravel\Cashier\Events\FirstPaymentPaid;
use Laravel\Cashier\FirstPayment\FirstPaymentHandler;
use Laravel\Cashier\Mollie\Contracts\UpdateMolliePayment;
use Symfony\Component\HttpFoundation\Response;

class FirstPaymentWebhookController extends BaseWebhookController
{
    /**
     * @param  Request  $request
     * @return Response
     *
     * @throws \Mollie\Api\Exceptions\ApiException Only in debug mode
     */
    public function handleWebhook(Request $request)
    {
        $payment = Cashier::$paymentModel::with('owner')->firstWhere('mollie_payment_id', $request->get('id'));

        $molliePayment = $this->getMolliePaymentById(
            $request->get('id'),
            owner: $payment->owner instanceof ProvidesOauthToken ? $payment->owner : null,
        );

        if ($molliePayment) {
            if ($molliePayment->isPaid()) {
                $order = (new FirstPaymentHandler($molliePayment))->execute();
                $molliePayment->webhookUrl = route('webhooks.mollie.aftercare');

                /** @var UpdateMolliePayment $updateMolliePayment */
                $updateMolliePayment = app()->make(UpdateMolliePayment::class);
                $molliePayment = $updateMolliePayment->execute(
                    $molliePayment,
                    $payment->owner instanceof ProvidesOauthToken ? $payment->owner : null
                );

                Event::dispatch(new FirstPaymentPaid($molliePayment, $order));
            } elseif ($molliePayment->isFailed()) {
                Event::dispatch(new FirstPaymentFailed($molliePayment));
            }
        }

        return new Response(null, 200);
    }
}
