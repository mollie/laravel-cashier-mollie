<?php

namespace Laravel\Cashier;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Console\Commands\CashierInstall;
use Laravel\Cashier\Console\Commands\CashierRun;
use Laravel\Cashier\Console\Commands\CashierUpdate;
use Laravel\Cashier\Console\Commands\SyncSubscriptionPlans;
use Laravel\Cashier\Coupon\ConfigCouponRepository;
use Laravel\Cashier\Coupon\Contracts\CouponRepository;
use Laravel\Cashier\Mollie\RegistersMollieInteractions;
use Laravel\Cashier\Order\Contracts\MaximumPayment as MaximumPaymentContract;
use Laravel\Cashier\Order\Contracts\MinimumPayment as MinimumPaymentContract;
use Laravel\Cashier\Plan\ConfigPlanRepository;
use Laravel\Cashier\Plan\Contracts\PlanRepository;
use Mollie\Api\MollieApiClient;

class CashierServiceProvider extends ServiceProvider
{
    use RegistersMollieInteractions;

    const PACKAGE_VERSION = '2.18.0';

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->registerCommands();
        $this->registerPublishing();
        $this->registerRoutes();
        $this->registerResources();

        $this->app->resolved(MollieApiClient::class, function (MollieApiClient $mollie) {
            return $mollie->addVersionString('MollieLaravelCashier/' . self::PACKAGE_VERSION);
        });

        $this->configureCurrency();
        $this->configureCurrencyLocale();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfig();

        $this->registerMollieInteractions($this->app);
        $this->app->bind(PlanRepository::class, ConfigPlanRepository::class);
        $this->app->singleton(CouponRepository::class, function () {
            return new ConfigCouponRepository(
                config('cashier_coupons.defaults'),
                config('cashier_coupons.coupons')
            );
        });
        $this->app->bind(MinimumPaymentContract::class, MinimumPayment::class);
        $this->app->bind(MaximumPaymentContract::class, MaximumPayment::class);

        $this->app->register(EventServiceProvider::class);
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            AboutCommand::add('Laravel Cashier Mollie', fn () => ['Version' => static::PACKAGE_VERSION]);

            $this->commands([
                CashierInstall::class,
                CashierRun::class,
                CashierUpdate::class,
                SyncSubscriptionPlans::class,
            ]);
        }
    }

    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishMigrations('cashier-migrations');
            $this->publishConfig('cashier-configs');
            $this->publishViews('cashier-views');
            $this->publishTranslations('cashier-translations');
            $this->publishUpdate('cashier-update');
        }
    }

    protected function registerRoutes(): void
    {
        if (Cashier::$registersRoutes) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/webhooks.php');
        }
    }

    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cashier');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'cashier');
    }

    protected function publishMigrations(string $tag)
    {
        if (Cashier::$runsMigrations) {
            $prefix = 'migrations/' . date('Y_m_d_His', time());

            $this->publishes([
                __DIR__ . '/../database/migrations/create_applied_coupons_table.php.stub' => database_path($prefix . '_create_applied_coupons_table.php'),
                __DIR__ . '/../database/migrations/create_redeemed_coupons_table.php.stub' => database_path($prefix . '_create_redeemed_coupons_table.php'),
                __DIR__ . '/../database/migrations/create_credits_table.php.stub' => database_path($prefix . '_create_credits_table.php'),
                __DIR__ . '/../database/migrations/create_orders_table.php.stub' => database_path($prefix . '_create_orders_table.php'),
                __DIR__ . '/../database/migrations/create_order_items_table.php.stub' => database_path($prefix . '_create_order_items_table.php'),
                __DIR__ . '/../database/migrations/create_subscriptions_table.php.stub' => database_path($prefix . '_create_subscriptions_table.php'),
                __DIR__ . '/../database/migrations/create_payments_table.php.stub' => database_path($prefix . '_create_payments_table.php'),
                __DIR__ . '/../database/migrations/create_refund_items_table.php.stub' => database_path($prefix . '_create_refund_items_table.php'),
                __DIR__ . '/../database/migrations/create_refunds_table.php.stub' => database_path($prefix . '_create_refunds_table.php'),
            ], $tag);
        }
    }

    protected function publishUpdate(string $tag)
    {
        if (Cashier::$runsMigrations) {
            $prefix = 'migrations/' . date('Y_m_d_His', time());

            $this->publishes([
                __DIR__ . '/../database/migrations/upgrade_to_cashier_v2.php.stub' => database_path($prefix . '_upgrade_to_cashier_v2.php'),
            ], $tag);
        }
    }

    protected function publishConfig(string $tag)
    {
        $this->publishes([
            __DIR__ . '/../config/cashier.php' => config_path('cashier.php'),
            __DIR__ . '/../config/cashier_coupons.php' => config_path('cashier_coupons.php'),
            __DIR__ . '/../config/cashier_plans.php' => config_path('cashier_plans.php'),
        ], $tag);
    }

    protected function publishTranslations(string $tag)
    {
        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/cashier'),
        ], $tag);
    }

    protected function publishViews(string $tag)
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => $this->app->basePath('resources/views/vendor/cashier'),
        ], $tag);
    }

    protected function mergeConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/cashier.php', 'cashier');
        $this->mergeConfigFrom(__DIR__ . '/../config/cashier_coupons.php', 'cashier_coupons');
        $this->mergeConfigFrom(__DIR__ . '/../config/cashier_plans.php', 'cashier_plans');
    }

    protected function configureCurrency()
    {
        $currency = config('cashier.currency', false);
        if ($currency) {
            Cashier::useCurrency($currency);
        }
    }

    protected function configureCurrencyLocale()
    {
        $locale = config('cashier.currency_locale', false);
        if ($locale) {
            Cashier::useCurrencyLocale($locale);
        }
    }
}
