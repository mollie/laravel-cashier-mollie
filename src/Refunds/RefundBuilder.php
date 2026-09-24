<?php

declare(strict_types=1);

namespace Laravel\Cashier\Refunds;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\RefundInitiated;
use Laravel\Cashier\Mollie\Contracts\CreateMollieRefund;
use Laravel\Cashier\Order\Order;
use Laravel\Cashier\Order\OrderItem;
use Laravel\Cashier\Order\OrderItemCollection;
use LogicException;
use Money\Money;
use Mollie\Api\Types\PaymentStatus;
use Mollie\Api\Types\RefundStatus;

class RefundBuilder
{
    /**
     * @var \Laravel\Cashier\Order\Order
     */
    protected Order $order;

    /**
     * @var \Laravel\Cashier\Refunds\RefundItemCollection
     */
    protected RefundItemCollection $items;

    /**
     * @var CreateMollieRefund
     */
    protected CreateMollieRefund $createMollieRefund;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->items = new RefundItemCollection;
        $this->createMollieRefund = app()->make(CreateMollieRefund::class);
    }

    public static function forOrder(Order $order): self
    {
        static::guardOrderIsPaid($order);

        return new static($order);
    }

    public static function forWholeOrder(Order $order): self
    {
        static::guardOrderIsPaid($order);
        $refund = new static($order);

        return $refund->addItems(RefundItemCollection::makeFromOrderItemCollection($order->items));
    }

    public function addItem(RefundItem $item): self
    {
        $this->items->add($item);

        return $this;
    }

    public function addItems(RefundItemCollection $items): self
    {
        $this->items = $this->items->concat($items);

        return $this;
    }

    public function addItemFromOrderItem(OrderItem $orderItem, array $overrides = []): self
    {
        return $this->addItem(Cashier::$refundItemModel::makeFromOrderItem($orderItem, $overrides));
    }

    public function addItemsFromOrderItemCollection(OrderItemCollection $orderItems, array $overrides = []): self
    {
        return $this->addItems(RefundItemCollection::makeFromOrderItemCollection($orderItems, $overrides));
    }

    protected static function guardOrderIsPaid(Order $order)
    {
        throw_unless(
            $order->mollie_payment_status === PaymentStatus::PAID,
            new LogicException('Only paid orders can be refunded')
        );
    }

    /**
     * The amount that will be charged back through Mollie. An order can be paid partly from
     * the owner's credit balance, in which case only the amount that was actually charged
     * (total_due) can be refunded through Mollie. The remainder is returned to the balance
     * once Mollie confirms the refund.
     */
    public function getMollieRefundAmount(): Money
    {
        return Money::min($this->items->getTotal(), $this->order->getTotalDueRefundable());
    }

    public function create(): Refund
    {
        $currency = $this->order->getCurrency();
        $refundAmount = $this->items->getTotal();
        $mollieRefundAmount = $this->getMollieRefundAmount();

        // A refund reverses order value, so it can never reverse more than the order has
        // left. Capping only the Mollie amount would still record the full item total
        // against the order, inflating amount_refunded and the credit orders built from it.
        $this->guardDoesNotExceedRemainingOrderValue($refundAmount, $currency);

        if (! $mollieRefundAmount->isPositive()) {
            return DB::transaction(function () use ($refundAmount) {
                $this->order = Cashier::$orderModel::whereKey($this->order->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                throw_unless(
                    $this->isCreditOnlyFollowUp($refundAmount),
                    new LogicException(
                        'There is nothing left to refund through Mollie for order ' . $this->order->getKey() . '. ' .
                        'An order that was paid entirely using credit, or that was already fully refunded, ' .
                        'cannot be refunded through Mollie.'
                    )
                );

                $refundRecord = $this->createRefundRecord(
                    'local_' . Str::uuid(),
                    RefundStatus::PENDING,
                    $this->order->getCurrency(),
                    new RefundItemCollection(
                        $this->items->map(fn (RefundItem $item) => $item->replicate())->all()
                    )
                );

                return $refundRecord->handleProcessed();
            });
        }

        $mollieRefund = $this->createMollieRefund->execute($this->order->mollie_payment_id, [
            'amount' => [
                'value' => money_to_decimal($mollieRefundAmount),
                'currency' => $currency,
            ],
        ]);

        return $this->createRefundRecord($mollieRefund->id, $mollieRefund->status, $currency);
    }

    protected function guardDoesNotExceedRemainingOrderValue(Money $refundAmount, string $currency): void
    {
        $refundable = $this->order->getTotalRefundable();

        throw_unless(
            $refundAmount->lessThanOrEqual($refundable),
            new LogicException(
                'Cannot refund ' . money_to_decimal($refundAmount) . ' ' . $currency .
                ' for order ' . $this->order->getKey() . ': only ' . money_to_decimal($refundable) .
                ' ' . $currency . ' of the order value is still refundable.'
            )
        );
    }

    protected function isCreditOnlyFollowUp(Money $refundAmount): bool
    {
        $amountRefundable = $this->order->getTotalRefundable();

        return $this->order->getAmountRefunded()->isPositive()
            && $refundAmount->isPositive()
            && $amountRefundable->isPositive()
            && $refundAmount->lessThanOrEqual($amountRefundable);
    }

    protected function createRefundRecord(
        string $mollieRefundId,
        string $status,
        string $currency,
        ?RefundItemCollection $items = null
    ): Refund
    {
        $refundRecord = Cashier::$refundModel::create([
            'owner_type' => $this->order->owner_type,
            'owner_id' => $this->order->owner_id,
            'original_order_id' => $this->order->getKey(),
            'total' => $this->items->getTotal()->getAmount(),
            'currency' => $currency,
            'mollie_refund_id' => $mollieRefundId,
            'mollie_refund_status' => $status,
        ]);

        $refundRecord->items()->saveMany($items ?? $this->items);

        event(new RefundInitiated($refundRecord));

        return $refundRecord;
    }
}
