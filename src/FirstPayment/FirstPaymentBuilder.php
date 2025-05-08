<?php

namespace Laravel\Cashier\FirstPayment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\FirstPayment\Actions\ActionCollection;
use Laravel\Cashier\FirstPayment\Traits\PaymentMethodString;
use Laravel\Cashier\Mollie\Contracts\CreateMolliePayment;
use Laravel\Cashier\Mollie\Contracts\UpdateMolliePayment;
use Mollie\Api\Types\SequenceType;

class FirstPaymentBuilder
{
    use PaymentMethodString;

    /**
     * The billable model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $owner;

    /**
     * A collection of BaseAction items. These actions will be executed by the FirstPaymentHandler
     *
     * @var ActionCollection
     */
    protected $actions;

    /**
     * Overrides the Mollie Payment payload
     *
     * @var array
     */
    protected $options;

    /**
     * The Mollie PaymentMethod
     *
     * @var array
     */
    protected $method;

    /**
     * The payment description.
     *
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $redirectUrl;

    /**
     * @var string
     */
    protected $webhookUrl;

    /**
     * @var \Mollie\Api\Resources\Payment|null
     */
    protected $molliePayment;

    /**
     * FirstPaymentBuilder constructor.
     *
     * @param  array  $options  Overrides the Mollie Payment payload
     */
    public function __construct(Model $owner, array $options = [])
    {
        $this->owner = $owner;
        $this->actions = new ActionCollection;
        $this->options = $options;
        $this->description = config('app.name', 'First payment');
        $this->redirectUrl = url(config('cashier.first_payment.redirect_url', config('cashier.redirect_url')));
        $this->webhookUrl = url(config('cashier.first_payment.webhook_url'));
    }

    /**
     * Define actions to be executed once the payment has been paid.
     *
     * @return $this
     */
    public function inOrderTo(array $actions = [])
    {
        $this->actions = new ActionCollection($actions);

        return $this;
    }

    /**
     * Build the Mollie Payment Payload
     *
     * @return array
     */
    public function getMolliePayload()
    {
        return array_filter(array_merge([
            'sequenceType' => SequenceType::SEQUENCETYPE_FIRST,
            'method' => $this->method,
            'customerId' => $this->owner->asMollieCustomer()->id,
            'locale' => Cashier::getLocale($this->owner),
            'description' => $this->description,
            'amount' => money_to_mollie_array($this->actions->total()),
            'webhookUrl' => $this->webhookUrl,
            'redirectUrl' => $this->redirectUrl,
            'metadata' => [
                'owner' => [
                    'type' => $this->owner->getMorphClass(),
                    'id' => $this->owner->getKey(),
                ],
            ],
        ], $this->options));
    }

    /**
     * @return \Mollie\Api\Resources\Payment
     */
    public function create()
    {
        $payload = $this->getMolliePayload();
        $untouchedRedirectUrl = $payload['redirectUrl'];
        $payload['redirectUrl'] = Str::replace(
            '{payment_id}',
            '---payment_id---', // In order to pass Mollie validation
            $payload['redirectUrl']
        );

        /** @var CreateMolliePayment $createMolliePayment */
        $createMolliePayment = app()->make(CreateMolliePayment::class);
        $this->molliePayment = $createMolliePayment->execute($payload);

        Cashier::$paymentModel::createFromMolliePayment(
            $this->molliePayment,
            $this->owner,
            $this->actions->toPlainArray()
        );

        // Parse and update redirectUrl
        if (Str::contains($untouchedRedirectUrl, '{payment_id}')) {
            $this->molliePayment->redirectUrl = Str::replace(
                '{payment_id}',
                $this->molliePayment->id,
                $untouchedRedirectUrl
            );

            /** @var UpdateMolliePayment $updateMolliePayment */
            $updateMolliePayment = app()->make(UpdateMolliePayment::class);
            $this->molliePayment = $updateMolliePayment->execute($this->molliePayment);
        }

        return $this->molliePayment;
    }

    /**
     * @param  array  $method
     * @return FirstPaymentBuilder
     */
    public function setFirstPaymentMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return FirstPaymentBuilder
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Override the default Mollie redirectUrl. Takes an absolute or relative url.
     *
     * @return $this
     */
    public function setRedirectUrl(string $redirectUrl)
    {
        $this->redirectUrl = url($redirectUrl);

        return $this;
    }

    /**
     * Override the default Mollie webhookUrl. Takes an absolute or relative url.
     *
     * @return $this
     */
    public function setWebhookUrl(string $webhookUrl)
    {
        $this->webhookUrl = url($webhookUrl);

        return $this;
    }

    /**
     * @return \Mollie\Api\Resources\Payment|null
     */
    public function getMolliePayment()
    {
        return $this->molliePayment;
    }
}
