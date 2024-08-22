<?php

namespace Laravel\Cashier\Tests;

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Coupon\RedeemedCouponCollection;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\Tests\Database\Factories\OrderFactory;
use Laravel\Cashier\Tests\Database\Factories\RedeemedCouponFactory;
use Laravel\Cashier\Events\MandateClearedFromBillable;
use Laravel\Cashier\Exceptions\MandateIsNotYetFinalizedException;
use Laravel\Cashier\Order\Invoice;
use Laravel\Cashier\SubscriptionBuilder\FirstPaymentSubscriptionBuilder;
use Laravel\Cashier\SubscriptionBuilder\MandatedSubscriptionBuilder;
use Laravel\Cashier\Tests\Database\Factories\SubscriptionFactory;
use Laravel\Cashier\Tests\Fixtures\User;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BillableTest extends BaseTestCase
{
    /** @test */
    public function testTaxPercentage()
    {
        $user = User::factory()->create([
            'tax_percentage' => 21.5,
        ]);

        $this->assertEquals(21.5, $user->taxPercentage());
    }

    /** @test */
    public function returnsFirstPaymentSubscriptionBuilderIfMandateIdOnOwnerIsNull()
    {
        $this->withConfiguredPlans();
        $user = $this->getUser(false, ['mollie_mandate_id' => null]);

        $builder = $user->newSubscription('default', 'monthly-10-1');

        $this->assertInstanceOf(FirstPaymentSubscriptionBuilder::class, $builder);
    }

    /** @test */
    public function returnsFirstPaymentSubscriptionBuilderIfOwnerMandateIsInvalid()
    {
        $this->withConfiguredPlans();
        $this->withMockedGetMollieCustomer();
        $this->withMockedGetMollieMandateRevoked(1, [['mandateId' => 'mdt_unique_revoked_mandate_id']]);

        $user = $this->getUser(false, [
            'mollie_mandate_id' => 'mdt_unique_revoked_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $builder = $user->newSubscription('default', 'monthly-10-1');

        $this->assertInstanceOf(FirstPaymentSubscriptionBuilder::class, $builder);
    }

    /** @test */
    public function throwExceptionIfMandateIsInPendingState()
    {
        $this->expectException(MandateIsNotYetFinalizedException::class);

        $this->withConfiguredPlans();
        $this->withMockedGetMollieCustomer();
        $this->withMockedGetMollieMandatePending();
        $user = $this->getMandatedUser(false);

        $user->newSubscriptionForMandateId('mdt_unique_mandate_id', 'main', 'monthly-10-1')->create();
    }

    /** @test */
    public function returnsDefaultSubscriptionBuilderIfOwnerHasValidMandateId()
    {
        $this->withConfiguredPlans();
        $this->withMockedGetMollieCustomer();
        $this->withMockedGetMollieMandateAccepted();
        $user = $this->getMandatedUser(false, [
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $builder = $user->newSubscription('default', 'monthly-10-1');

        $this->assertInstanceOf(MandatedSubscriptionBuilder::class, $builder);
    }

    /** @test */
    public function canRetrieveRedeemedCoupons()
    {
        $user = User::factory()->create();

        $redeemedCoupons = $user->redeemedCoupons;
        $this->assertInstanceOf(RedeemedCouponCollection::class, $redeemedCoupons);
        $this->assertCount(0, $redeemedCoupons);
    }

    /** @test */
    public function canRedeemCouponForExistingSubscription()
    {
        $this->withConfiguredPlans();
        $this->withMockedCouponRepository(); // 'test-coupon'
        $this->withMockedGetMollieCustomer(3);
        $this->withMockedGetMollieMandateAccepted(3);

        $user = $this->getMandatedUser(true, [
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);
        $subscription = $user->newSubscription('default', 'monthly-10-1')->create();
        $this->assertEquals(0, $user->redeemedCoupons()->count());

        $user = $user->redeemCoupon('test-coupon', 'default', false);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->redeemedCoupons()->count());
        $this->assertEquals(1, $subscription->redeemedCoupons()->count());
        $this->assertEquals(0, $subscription->appliedCoupons()->count());
    }

    /** @test */
    public function canRedeemCouponAndRevokeOtherCoupons()
    {
        $this->withConfiguredPlans();
        $this->withMockedCouponRepository(); // 'test-coupon'
        $this->withMockedGetMollieCustomer(3);
        $this->withMockedGetMollieMandateAccepted(3);

        $user = $this->getMandatedUser(true, [
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $subscription = $user->newSubscription('default', 'monthly-10-1')->create();
        $subscription->redeemedCoupons()->saveMany(RedeemedCouponFactory::times(2)->make());
        $this->assertEquals(2, $subscription->redeemedCoupons()->active()->count());
        $this->assertEquals(0, $subscription->appliedCoupons()->count());

        $user = $user->redeemCoupon('test-coupon', 'default', true);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->redeemedCoupons()->active()->count());
        $this->assertEquals(1, $subscription->redeemedCoupons()->active()->count());
        $this->assertEquals(0, $subscription->appliedCoupons()->count());
    }

    /** @test */
    public function clearMollieMandate()
    {
        Event::fake();
        $user = $this->getUser(true, ['mollie_mandate_id' => 'foo-bar']);
        $this->assertEquals('foo-bar', $user->mollieMandateId());

        $user->clearMollieMandate();

        $this->assertNull($user->mollieMandateId());
        Event::assertDispatched(MandateClearedFromBillable::class, function ($e) use ($user) {
            $this->assertEquals('foo-bar', $e->oldMandateId);
            $this->assertTrue($e->owner->is($user));

            return true;
        });
    }

    /** @test */
    public function canFindInvoice()
    {
        $user = $this->getUser();
        OrderFactory::times(2)->create([
            'owner_id' => $user->id,
            'owner_type' => $user->getMorphClass(),
        ])->first()->update(['number' => 'find_invoice_test_1']);

        $invoice = $user->findInvoice('find_invoice_test_1');

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('find_invoice_test_1', $invoice->id());
    }

    /** @test */
    public function findInvoiceReturnsNullIfInvoiceDoesNotExist()
    {
        $user = $this->getUser();

        $invoice = $user->findInvoice('does_not_exist');

        $this->assertNull($invoice);
    }

    /** @test */
    public function findInvoiceThrowsExceptionIfInvoiceExistButIsAssociatedWithOtherBillableModel()
    {
        $userA = $this->getUser();
        OrderFactory::new()->create([
            'number' => 'foo-bar',
            'owner_id' => $userA->id,
        ]);

        $userB = $this->getUser();
        $this->assertTrue($userA->isNot($userB));
        $this->expectException(AccessDeniedHttpException::class);

        $userB->findInvoice('foo-bar');
    }

    /** @test */
    public function canFindInvoiceUsingFindInvoiceOrFail()
    {
        $user = $this->getUser();
        OrderFactory::times(2)->create([
            'owner_id' => $user->id,
            'owner_type' => $user->getMorphClass(),
        ])->first()->update(['number' => 'find_invoice_test_1']);

        $invoice = $user->findInvoiceOrFail('find_invoice_test_1');

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('find_invoice_test_1', $invoice->id());
    }

    /** @test */
    public function findInvoiceOrFailThrowsExceptionWhenNotFindingTheInvoice()
    {
        $user = $this->getUser();

        $this->expectException(NotFoundHttpException::class);

        $user->findInvoiceOrFail('does_not_exist');
    }

    /** @test */
    public function findInvoiceOrFailThrowsExceptionIfInvoiceExistButIsAssociatedWithOtherBillableModel()
    {
        $userA = $this->getUser();
        OrderFactory::new()->create([
            'number' => 'foo-bar',
            'owner_id' => $userA->id,
        ]);

        $userB = $this->getUser();
        $this->assertTrue($userA->isNot($userB));
        $this->expectException(AccessDeniedHttpException::class);

        $userB->findInvoiceOrFail('foo-bar');
    }

    /** @test */
    public function canFindInvoiceByOrderId()
    {
        $user = $this->getUser();

        $user->orders()->saveMany([
            OrderFactory::new()->make([
                'id' => 1,
                'number' => '2018-0000-0001',
            ]),
            OrderFactory::new()->make([
                'id' => 2,
                'number' => '2018-0000-0002',
            ]),
        ]);

        $invoice = $user->findInvoiceByOrderId(2);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('2018-0000-0002', $invoice->id());
    }

    /** @test */
    public function canFindInvoiceByOrderIdUsingFindInvoiceByOrderIdOrFail()
    {
        $user = $this->getUser();

        $user->orders()->saveMany([
            OrderFactory::new()->make([
                'id' => 1,
                'number' => '2018-0000-0001',
            ]),
            OrderFactory::new()->make([
                'id' => 2,
                'number' => '2018-0000-0002',
            ]),
        ]);

        $invoice = $user->findInvoiceByOrderIdOrFail(2);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('2018-0000-0002', $invoice->id());
    }

    /** @test */
    public function testHasActiveSubscription()
    {
        $this->withConfiguredPlans();
        $this->withMockedCouponRepository(); // 'test-coupon'
        $this->withMockedGetMollieCustomer(3);
        $this->withMockedGetMollieMandateAccepted(3);

        $user = $this->getMandatedUser(true, [
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);
        $user->newSubscription('default', 'monthly-10-1')->create();

        $this->assertTrue($user->hasActiveSubscription());
    }

    /** @test */
    public function testHasActiveSubscriptionsFalse()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->hasActiveSubscription());
    }
}
