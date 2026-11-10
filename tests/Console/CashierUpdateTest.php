<?php

namespace Laravel\Cashier\Tests\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Laravel\Cashier\CashierServiceProvider;
use Laravel\Cashier\Tests\BaseTestCase;
use Laravel\Cashier\Tests\Database\Factories\SubscriptionFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class CashierUpdateTest extends BaseTestCase
{
    #[Test]
    public function upgrading_v2_preserves_subscriptions_and_adds_v3_columns(): void
    {
        $subscription = SubscriptionFactory::new()->create();
        $migration = require __DIR__.'/../../database/migrations/upgrade_to_cashier_v3.php.stub';
        $migration->down();
        $this->assertFalse(Schema::hasColumn('subscriptions', 'cancellation_reason'));

        $migration->up();

        foreach ($migration::METADATA_FIELD_TABLES as $table) {
            $this->assertTrue(Schema::hasColumn($table, 'metadata'));
        }
        $this->assertTrue(Schema::hasColumn('subscriptions', 'cancellation_reason'));
        $this->assertSame($subscription->plan, $subscription->fresh()->plan);
        $this->assertNull($subscription->fresh()->cancellation_reason);
        $migration->down();
        $this->assertFalse(Schema::hasColumn('subscriptions', 'metadata'));
        $this->assertFalse(Schema::hasColumn('subscriptions', 'cancellation_reason'));
    }

    #[Test]
    #[DataProvider('confirmationAnswers')]
    public function production_update_publishes_only_after_confirmation(bool $confirmed): void
    {
        $this->app['env'] = 'production';
        $directory = sys_get_temp_dir().'/cashier-update-'.bin2hex(random_bytes(8));
        mkdir($directory.'/migrations', 0777, true);
        $this->app->useDatabasePath($directory);
        $this->app->getProvider(CashierServiceProvider::class)->boot();
        try {
            $this->artisan('cashier:update')->expectsConfirmation('Proceed updating Cashier?', $confirmed ? 'yes' : 'no')->assertSuccessful()->run();
            $this->assertCount($confirmed ? 1 : 0, glob($directory.'/migrations/*_upgrade_to_cashier_v3.php'));
        } finally {
            (new Filesystem)->deleteDirectory($directory);
        }
    }

    public static function confirmationAnswers(): array
    {
        return [[false], [true]];
    }
}
