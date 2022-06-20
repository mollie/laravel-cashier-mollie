<?php

namespace Laravel\Cashier\Tests;

use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Coupon\RedeemedCouponCollection;
use Laravel\Cashier\Events\MandateClearedFromBillable;
use Laravel\Cashier\Exceptions\MandateIsNotYetFinalizedException;
use Laravel\Cashier\Mollie\Contracts\GetMollieCustomer;
use Laravel\Cashier\Mollie\Contracts\GetMollieMandate;
use Laravel\Cashier\Order\Invoice;
use Laravel\Cashier\SubscriptionBuilder\FirstPaymentSubscriptionBuilder;
use Laravel\Cashier\SubscriptionBuilder\MandatedSubscriptionBuilder;
use Laravel\Cashier\Tests\Fixtures\User;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Customer;
use Mollie\Api\Resources\Mandate;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BillableTest extends BaseTestCase
{
    /** @test */
    public function testTaxPercentage()
    {
        $this->withPackageMigrations();
        $user = factory(User::class)->create([
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
        $this->withPackageMigrations();
        $this->withMockedGetMollieCustomer();
        $this->withMockedGetMollieMandateRevoked();

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
        $this->withMockedGetMolliePendingMandate();
        $user = $this->getMandatedUser(false);

        $user->newSubscriptionForMandateId('mdt_unique_mandate_id', 'main', 'monthly-10-1')->create();
    }

    /** @test */
    public function returnsDefaultSubscriptionBuilderIfOwnerHasValidMandateId()
    {
        $this->withConfiguredPlans();
        $this->withMockedGetMollieCustomer();
        $this->withMockedGetMollieMandate();
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
        $this->withPackageMigrations();

        $user = factory(User::class)->create();

        $redeemedCoupons = $user->redeemedCoupons;
        $this->assertInstanceOf(RedeemedCouponCollection::class, $redeemedCoupons);
        $this->assertCount(0, $redeemedCoupons);
    }

    /** @test */
    public function canRedeemCouponForExistingSubscription()
    {
        $this->withPackageMigrations();
        $this->withConfiguredPlans();
        $this->withMockedCouponRepository(); // 'test-coupon'
        $this->withMockedGetMollieCustomerThreeTimes();
        $this->withMockedGetMollieMandateThreeTimes();

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
        $this->withPackageMigrations();
        $this->withConfiguredPlans();
        $this->withMockedCouponRepository(); // 'test-coupon'
        $this->withMockedGetMollieCustomerThreeTimes();
        $this->withMockedGetMollieMandateThreeTimes();

        $user = $this->getMandatedUser(true, [
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
            'mollie_customer_id' => 'cst_unique_customer_id',
        ]);

        $subscription = $user->newSubscription('default', 'monthly-10-1')->create();
        $subscription->redeemedCoupons()->saveMany(factory(Cashier::$redeemedCouponModel, 2)->make());
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
        $this->withPackageMigrations();
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
        $this->withPackageMigrations();
        $user = $this->getUser();
        factory(Cashier::$orderModel, 2)->create([
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
        $this->withPackageMigrations();
        $user = $this->getUser();

        $invoice = $user->findInvoice('does_not_exist');

        $this->assertNull($invoice);
    }

    /** @test */
    public function findInvoiceThrowsExceptionIfInvoiceExistButIsAssociatedWithOtherBillableModel()
    {
        $this->withPackageMigrations();
        $userA = $this->getUser();
        factory(Cashier::$orderModel)->create([
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
        $this->withPackageMigrations();
        $user = $this->getUser();
        factory(Cashier::$orderModel, 2)->create([
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
        $this->withPackageMigrations();
        $user = $this->getUser();

        $this->expectException(NotFoundHttpException::class);

        $user->findInvoiceOrFail('does_not_exist');
    }

    /** @test */
    public function findInvoiceOrFailThrowsExceptionIfInvoiceExistButIsAssociatedWithOtherBillableModel()
    {
        $this->withPackageMigrations();
        $userA = $this->getUser();
        factory(Cashier::$orderModel)->create([
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
        $this->withPackageMigrations();
        $user = $this->getUser();

        $user->orders()->saveMany([
            factory(Cashier::$orderModel)->make([
                'id' => 1,
                'number' => '2018-0000-0001',
            ]),
            factory(Cashier::$orderModel)->make([
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
        $this->withPackageMigrations();
        $user = $this->getUser();

        $user->orders()->saveMany([
            factory(Cashier::$orderModel)->make([
                'id' => 1,
                'number' => '2018-0000-0001',
            ]),
            factory(Cashier::$orderModel)->make([
                'id' => 2,
                'number' => '2018-0000-0002',
            ]),
        ]);

        $invoice = $user->findInvoiceByOrderIdOrFail(2);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals('2018-0000-0002', $invoice->id());
    }

    protected function withMockedGetMollieCustomer(): void
    {
        $this->mock(GetMollieCustomer::class, function ($mock) {
            $customer = new Customer(new MollieApiClient);
            $customer->id = 'cst_unique_customer_id';

            return $mock->shouldReceive('execute')->with('cst_unique_customer_id')->once()->andReturn($customer);
        });
    }

    protected function withMockedGetMollieCustomerThreeTimes(): void
    {
        $this->mock(GetMollieCustomer::class, function ($mock) {
            $customer = new Customer(new MollieApiClient);
            $customer->id = 'cst_unique_customer_id';

            return $mock->shouldReceive('execute')->with('cst_unique_customer_id')->times(3)->andReturn($customer);
        });
    }

    protected function withMockedGetMollieMandateRevoked(): void
    {
        $this->mock(GetMollieMandate::class, function ($mock) {
            $mandate = new Mandate(new MollieApiClient);
            $mandate->id = 'mdt_unique_revoked_mandate_id';
            $mandate->status = 'invalid';

            return $mock->shouldReceive('execute')->with('cst_unique_customer_id', 'mdt_unique_revoked_mandate_id')->once()->andReturn($mandate);
        });
    }

    protected function withMockedGetMollieMandate(): void
    {
        $this->mock(GetMollieMandate::class, function ($mock) {
            $mandate = new Mandate(new MollieApiClient);
            $mandate->id = 'mdt_unique_mandate_id';
            $mandate->status = 'valid';
            $mandate->method = 'directdebit';

            return $mock->shouldReceive('execute')->with('cst_unique_customer_id', 'mdt_unique_mandate_id')->once()->andReturn($mandate);
        });
    }

    protected function withMockedGetMolliePendingMandate(): void
    {
        $this->mock(GetMollieMandate::class, function ($mock) {
            $mandate = new Mandate(new MollieApiClient);
            $mandate->id = 'mdt_unique_mandate_id';
            $mandate->status = 'pending';
            $mandate->method = 'directdebit';

            return $mock->shouldReceive('execute')->with('cst_unique_customer_id', 'mdt_unique_mandate_id')->once()->andReturn($mandate);
        });
    }

    protected function withMockedGetMollieMandateThreeTimes(): void
    {
        $this->mock(GetMollieMandate::class, function ($mock) {
            $mandate = new Mandate(new MollieApiClient);
            $mandate->id = 'mdt_unique_revoked_mandate_id';
            $mandate->status = 'valid';
            $mandate->method = 'directdebit';

            return $mock->shouldReceive('execute')->with('cst_unique_customer_id', 'mdt_unique_mandate_id')->times(3)->andReturn($mandate);
        });
    }
}
