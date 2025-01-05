<?php

namespace Laravel\Cashier\Tests;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Laravel\Cashier\CashierServiceProvider;
use Laravel\Cashier\Coupon\CouponOrderItemPreprocessor;
use Laravel\Cashier\Plan\AdvancedIntervalGenerator;
use Laravel\Cashier\Tests\Fixtures\User;
use Laravel\Cashier\Tests\Traits\InteractsWithMocks;
use Mollie\Laravel\MollieServiceProvider;
use Money\Currency;
use Money\Money;
use Orchestra\Testbench\TestCase;

abstract class BaseTestCase extends TestCase
{
    use InteractsWithMocks;

    protected $interactWithMollieAPI = false;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupDatabase();
        $this->withFixtureModels();

        config(['cashier.webhook_url' => 'https://www.example.com/webhook']);
        config(['cashier.aftercare_webhook_url' => 'https://www.example.com/aftercare-webhook']);
        config(['cashier.first_payment.webhook_url' => 'https://www.example.com/mandate-webhook']);
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            CashierServiceProvider::class,
            MollieServiceProvider::class,
        ];
    }

    protected function setupDatabase(): void
    {
        $migrations_dir = __DIR__ . '/../database/migrations';

        $this->runMigrations(
            collect(
                [
                    [
                        'class' => \Laravel\Cashier\Tests\Database\Migrations\CreateUsersTable::class,
                        'file_path' => __DIR__ . '/Database/Migrations/create_users_table.php'
                    ],
                    [
                        'class' => '\CreateSubscriptionsTable',
                        'file_path' => $migrations_dir . '/create_subscriptions_table.php.stub',
                    ],
                    [
                        'class' => '\CreateOrderItemsTable',
                        'file_path' => $migrations_dir . '/create_order_items_table.php.stub',
                    ],
                    [
                        'class' => '\CreateOrdersTable',
                        'file_path' => $migrations_dir . '/create_orders_table.php.stub',
                    ],
                    [
                        'class' => '\CreateCreditsTable',
                        'file_path' => $migrations_dir . '/create_credits_table.php.stub',
                    ],
                    [
                        'class' => '\CreateRedeemedCouponsTable',
                        'file_path' => $migrations_dir . '/create_redeemed_coupons_table.php.stub',
                    ],
                    [
                        'class' => '\CreateAppliedCouponsTable',
                        'file_path' => $migrations_dir . '/create_applied_coupons_table.php.stub',
                    ],
                    [
                        'class' => '\CreatePaymentsTable',
                        'file_path' => $migrations_dir . '/create_payments_table.php.stub',
                    ],
                    [
                        'class' => '\CreateRefundItemsTable',
                        'file_path' => $migrations_dir . '/create_refund_items_table.php.stub',
                    ],
                    [
                        'class' => '\CreateRefundsTable',
                        'file_path' => $migrations_dir . '/create_refunds_table.php.stub',
                    ],
                    [
                        'class' => '\AlterOrderItemsAddIdentifier',
                        'file_path' => $migrations_dir . '/alter_order_items_add_identifier.php.stub',
                    ],
                ]
            )
        );
    }
    /**
     * Runs a collection of migrations.
     *
     * @param  Collection  $migrations
     */
    protected function runMigrations(Collection $migrations)
    {
        $migrations->each(fn ($info) => $this->runMigration($info['class'], $info['file_path']));
    }

    /**
     * @param  string  $class
     * @param  string  $file_path
     */
    protected function runMigration(string $className, string $file_path)
    {
        require_once $file_path;
        (new $className)->up();
    }

    /**
     * Assert that a Carbon datetime is approximately equal to another Carbon datetime.
     *
     * @param  \Carbon\Carbon  $expected
     * @param  \Carbon\Carbon  $actual
     * @param  int  $precision_seconds
     */
    protected function assertCarbon(Carbon $expected, Carbon $actual, int $precision_seconds = 5)
    {
        $expected_min = $expected->copy()->subSeconds($precision_seconds)->startOfSecond();
        $expected_max = $expected->copy()->addSeconds($precision_seconds)->startOfSecond();

        $actual = $actual->copy()->startOfSecond();

        $this->assertTrue(
            $actual->between($expected_min, $expected_max),
            "Actual datetime [{$actual}] differs more than {$precision_seconds} seconds from expected [{$expected}]."
        );
    }

    /**
     * @return $this
     */
    protected function withFixtureModels()
    {
        config(['cashier.billable_model' => User::class]);

        return $this;
    }

    /**
     * Set the system test datetime.
     *
     * @param  Carbon|string  $now
     * @return $this
     */
    protected function withTestNow($now)
    {
        if (is_string($now)) {
            $now = Carbon::parse($now);
        }
        Carbon::setTestNow($now);

        return $this;
    }

    /**
     * Configure some test plans.
     *
     * @return $this
     */
    protected function withConfiguredPlans()
    {
        config([
            'cashier_plans' => [
                'defaults' => [
                    'first_payment' => [
                        'redirect_url' => 'https://www.example.com',
                        'webhook_url' => 'https://www.example.com/webhooks/mollie/first-payment',
                        'method' => ['ideal'],
                        'amount' => [
                            'value' => '0.05',
                            'currency' => 'EUR',
                        ],
                        'description' => 'Test mandate payment',
                    ],
                    'order_item_preprocessors' => [
                        CouponOrderItemPreprocessor::class,
                    ],
                ],
                'plans' => [
                    'monthly-10-1' => [
                        'amount' => [
                            'currency' => 'EUR',
                            'value' => '10.00',
                        ],
                        'interval' => '1 month',
                        'description' => 'Monthly payment',
                    ],
                    'monthly-10-2' => [
                        'amount' => [
                            'currency' => 'EUR',
                            'value' => '20.00',
                        ],
                        'interval' => '2 months',
                        'method' => 'directdebit',
                        'description' => 'Bimonthly payment',
                    ],
                    'monthly-20-1' => [
                        'amount' => [
                            'currency' => 'EUR',
                            'value' => '20.00',
                        ],
                        'interval' => '1 month',
                        'description' => 'Monthly payment premium',
                    ],
                    'weekly-20-1' => [
                        'amount' => [
                            'currency' => 'EUR',
                            'value' => '20.00',
                        ],
                        'interval' => '1 weeks',
                        'description' => 'Twice as expensive monthly subscription',
                    ],
                ],
            ],
        ]);

        return $this;
    }

    /**
     * Configure some test plans.
     *
     * @return $this
     */
    protected function withConfiguredPlansWithIntervalArray()
    {
        config([
            'cashier_plans' => [
                'defaults' => [
                    'first_payment' => [
                        'redirect_url' => 'https://www.example.com',
                        'webhook_url' => 'https://www.example.com/webhooks/mollie/first-payment',
                        'method' => 'ideal',
                        'amount' => [
                            'value' => '0.05',
                            'currency' => 'EUR',
                        ],
                        'description' => 'Test mandate payment',
                    ],
                ],
                'plans' => [
                    'withfixedinterval-10-1' => [
                        'amount' => [
                            'currency' => 'EUR',
                            'value' => '10.00',
                        ],
                        'interval' => [
                            'generator' => AdvancedIntervalGenerator::class,
                            'value' => 1,
                            'period' => 'month',
                            'monthOverflow' => false,
                        ],
                        'description' => 'Monthly payment',
                    ],
                    'withoutfixedinterval-10-1' => [
                        'amount' => [
                            'currency' => 'EUR',
                            'value' => '10.00',
                        ],
                        'interval' => [
                            'generator' => AdvancedIntervalGenerator::class,
                            'value' => 1,
                            'period' => 'month',
                            'monthOverflow' => true,
                        ],
                        'description' => 'Monthly payment',
                    ],
                ],
            ],
        ]);

        return $this;
    }

    protected function getMandatedCustomerId()
    {
        return env('MANDATED_CUSTOMER_DIRECTDEBIT');
    }

    protected function getMandatedUser($persist = true, $overrides = [])
    {
        return $this->getCustomerUser($persist, array_merge([
            'mollie_mandate_id' => 'mdt_unique_mandate_id',
        ], $overrides));
    }

    protected function getCustomerUser($persist = true, $overrides = [])
    {
        return $this->getUser($persist, array_merge([
            'mollie_customer_id' => 'cst_unique_customer_id',
        ], $overrides));
    }

    /**
     * @param  bool  $persist
     * @param  array  $overrides
     * @return User
     */
    protected function getUser($persist = true, $overrides = [])
    {
        $user = User::factory()->make($overrides);

        if ($persist) {
            $user->save();
        }

        return $user;
    }

    protected function getMandatePaymentID()
    {
        return env('MANDATE_PAYMENT_PAID_ID');
    }

    protected function getMandateId()
    {
        return env('MANDATED_CUSTOMER_DIRECTDEBIT_MANDATE_ID');
    }

    /**
     * @param  int  $value
     * @param  string  $currency
     * @param  \Money\Money  $money
     */
    protected function assertMoney(int $value, string $currency, Money $money)
    {
        $this->assertEquals($currency, $money->getCurrency()->getCode());
        $this->assertEquals($money->getAmount(), $value);
        $this->assertTrue((new Money($value, new Currency($currency)))->equals($money));
    }

    /**
     * @param  int  $value
     * @param  \Money\Money  $money
     */
    protected function assertMoneyEURCents(int $value, Money $money)
    {
        $this->assertMoney($value, 'EUR', $money);
    }
}
