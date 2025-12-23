<?php

namespace Laravel\Cashier\Tests\Coupon;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\Coupon\Contracts\CouponRepository;
use Laravel\Cashier\Coupon\Coupon;
use Laravel\Cashier\Coupon\CouponOrderItemPreprocessor;
use Laravel\Cashier\Coupon\PercentageDiscountHandler;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\OrderItemFactory;
use Laravel\Cashier\Tests\Database\Factories\SubscriptionFactory;

class PercentageCouponTest extends BaseTestCase
{
    /** @test */
    public function couponCalculatesTheRightPrice()
    {
        $couponHandler = new PercentageDiscountHandler;

        $context = [
            'description' => 'Percentage coupon',
            'percentage' => 20,
        ];

        $coupon = new Coupon(
            'percentage-coupon',
            $couponHandler,
            $context
        );

        $this->withMockedCouponRepository($coupon, $couponHandler, $context);

        /** @var Subscription $subscription */
        $subscription = SubscriptionFactory::new()->create();
        $item = OrderItemFactory::new()->make();
        $subscription->orderItems()->save($item);

        /** @var \Laravel\Cashier\Coupon\Coupon $coupon */
        $coupon = app()->make(CouponRepository::class)->findOrFail('percentage-coupon');
        $redeemedCoupon = $coupon->redeemFor($subscription);
        $preprocessor = new CouponOrderItemPreprocessor();

        $result = $preprocessor->handle($item->toCollection());

        // Item: unit_price=12150 (€121.50), tax=21.5%
        // Subtotal: €121.50, Total: €147.62
        // Discount should be 20% of subtotal (€121.50) = €24.30, not 20% of total (€147.62) = €29.52
        $this->assertEquals(-2430, $result[1]->unit_price);
    }

    /** @test */
    public function percentageCouponWithNoTaxCalculatesDiscountFromSubtotalNotTotal()
    {
        // 50% coupon on €150 item with 21% VAT
        // Expected: Discount should be -€75 (50% of €150 subtotal)

        $couponHandler = new PercentageDiscountHandler;

        $context = [
            'description' => '50% off coupon',
            'percentage' => 50,
            'no_tax' => true, // Default behavior
        ];

        $coupon = new Coupon(
            'percentage-50-off',
            $couponHandler,
            $context
        );

        $this->withMockedCouponRepository($coupon, $couponHandler, $context);

        /** @var Subscription $subscription */
        $subscription = SubscriptionFactory::new()->create();

        // Create item: €150 subtotal, 21% VAT = €31.50, total = €181.50
        $item = OrderItemFactory::new()->make([
            'unit_price' => 15000, // €150.00
            'quantity' => 1,
            'tax_percentage' => 21.0,
            'currency' => 'EUR',
        ]);
        $subscription->orderItems()->save($item);

        // Verify original item calculations
        $this->assertMoneyEURCents(15000, $item->getSubtotal()); // €150.00
        $this->assertMoneyEURCents(3150, $item->getTax()); // €31.50 (21% of €150)
        $this->assertMoneyEURCents(18150, $item->getTotal()); // €181.50

        /** @var \Laravel\Cashier\Coupon\Coupon $coupon */
        $coupon = app()->make(CouponRepository::class)->findOrFail('percentage-50-off');
        $redeemedCoupon = $coupon->redeemFor($subscription);
        $preprocessor = new CouponOrderItemPreprocessor();

        $result = $preprocessor->handle($item->toCollection());

        // Should have 2 items: original item + discount item
        $this->assertCount(2, $result);

        $discountItem = $result[1];

        // Expected: Discount should be -€75.00 (50% of €150 subtotal, not €181.50 total)
        // Currently buggy: Discount is -€90.75 (50% of €181.50 total)
        $this->assertMoneyEURCents(-7500, $discountItem->getSubtotal());
        $this->assertEquals(0, $discountItem->tax_percentage); // no_tax = true

        // Calculate final totals
        $collection = $result;
        $finalSubtotal = $collection->sum('subtotal');
        $finalTax = $collection->sum('tax');
        $finalTotal = $collection->sum('total');

        // Expected final values:
        // Subtotal: €150 - €75 = €75 ✓
        // Tax: Original item tax (€31.50) - Discount item tax (€0, because no_tax=true) = €31.50
        // Note: With no_tax=true, the discount item has 0% tax, so it doesn't reduce tax from original item
        // Total: €75 + €31.50 = €106.50
        // The main fix is that discount is calculated from subtotal (€150) not total (€181.50)
        $this->assertEquals(7500, $finalSubtotal);
        // With no_tax=true, tax is not reduced because discount item has 0% tax
        $this->assertEquals(3150, $finalTax); // Original item tax remains (21% of €150)
        $this->assertEquals(10650, $finalTotal);
    }

    /** @test */
    public function percentageCouponWithTaxCalculatesDiscountFromSubtotalNotTotal()
    {
        // 50% coupon on €150 item with 21% VAT, no_tax = false
        // Expected: Discount should be -€75 (50% of €150 subtotal), then tax is applied to the discount

        $couponHandler = new PercentageDiscountHandler;

        $context = [
            'description' => '50% off coupon',
            'percentage' => 50,
            'no_tax' => false, // Tax will be applied to discount
        ];

        $coupon = new Coupon(
            'percentage-50-off-taxed',
            $couponHandler,
            $context
        );

        $this->withMockedCouponRepository($coupon, $couponHandler, $context);

        /** @var Subscription $subscription */
        $subscription = SubscriptionFactory::new()->create();

        // Create item: €150 subtotal, 21% VAT = €31.50, total = €181.50
        $item = OrderItemFactory::new()->make([
            'unit_price' => 15000, // €150.00
            'quantity' => 1,
            'tax_percentage' => 21.0,
            'currency' => 'EUR',
        ]);
        $subscription->orderItems()->save($item);

        /** @var \Laravel\Cashier\Coupon\Coupon $coupon */
        $coupon = app()->make(CouponRepository::class)->findOrFail('percentage-50-off-taxed');
        $redeemedCoupon = $coupon->redeemFor($subscription);
        $preprocessor = new CouponOrderItemPreprocessor();

        $result = $preprocessor->handle($item->toCollection());

        // Should have 2 items: original item + discount item
        $this->assertCount(2, $result);

        $discountItem = $result[1];

        // Expected: Discount subtotal should be -€75.00 (50% of €150 subtotal)
        // Tax will be applied: -€75 * 21% = -€15.75
        // Discount total = -€90.75
        $this->assertMoneyEURCents(-7500, $discountItem->getSubtotal());
        $this->assertEquals(21.0, $discountItem->tax_percentage);
        $this->assertMoneyEURCents(-1575, $discountItem->getTax()); // -€15.75
        $this->assertMoneyEURCents(-9075, $discountItem->getTotal()); // -€90.75

        // Calculate final totals
        $collection = $result;
        $finalSubtotal = $collection->sum('subtotal');
        $finalTax = $collection->sum('tax');
        $finalTotal = $collection->sum('total');

        // Expected final values:
        // Subtotal: €150 - €75 = €75
        // Tax: €31.50 - €15.75 = €15.75 (21% of €75)
        // Total: €75 + €15.75 = €90.75
        $this->assertEquals(7500, $finalSubtotal);
        $this->assertEquals(1575, $finalTax);
        $this->assertEquals(9075, $finalTotal);
    }
}
