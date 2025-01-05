<?php

namespace Laravel\Cashier\Console\Commands;

use Illuminate\Console\Command;

class CashierInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashier:install
                            {--T|template : include publishing the invoice template}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Cashier Mollie';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (app()->environment('production')) {
            $this->alert('Running in production mode.');
            if (!$this->confirm('Proceed installing Cashier?')) {
                return;
            }
        }

        $this->comment('Publishing Cashier migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'cashier-migrations']);

        $this->comment('Publishing Cashier configuration files...');
        $this->callSilent('vendor:publish', ['--tag' => 'cashier-configs']);
        $this->publishLocalMigrations();

        if ($this->option('template')) {
            $this->callSilent('vendor:publish', ['--tag' => 'cashier-views']);
        } else {
            $this->info(
                'You can publish the Cashier invoice template so you can modify it. '
                .'Note that this will exclude your template copy from updates by the package maintainers.'
            );

            if ($this->confirm('Publish Cashier invoice template?')) {
                $this->comment('Publishing Cashier invoice template...');
                $this->callSilent('vendor:publish', ['--tag' => 'cashier-views']);
            }
        }

        $this->info('Cashier was installed successfully.');
    }

    /**
     * Copy local migrations to the application.
     */
    private function publishLocalMigrations(): void
    {
        if (! class_exists('AlterOrderItemsAddIdentifier')) {
            copy(
                __DIR__.'/../../../database/migrations/alter_order_items_add_metadata.php.stub',
                database_path('migrations/'.date('Y_m_d_His', time()).'_alter_order_items_add_metadata.php')
            );
        }
    }
}
