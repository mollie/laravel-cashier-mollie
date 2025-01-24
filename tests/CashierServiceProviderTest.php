<?php

namespace Laravel\Cashier\Tests;

use Laravel\Cashier\Cashier;
use Laravel\Cashier\CashierServiceProvider;

class CashierServiceProviderTest extends BaseTestCase
{
    /** @test */
    public function can_optionally_set_currency_in_config()
    {
        $this->assertEquals('INEXISTENT', config('cashier.currency', 'INEXISTENT'));

        $this->assertEquals('€', Cashier::usesCurrencySymbol());
        $this->assertEquals('eur', Cashier::usesCurrency());

        config(['cashier.currency' => 'usd']);
        $this->rebootCashierServiceProvider();

        $this->assertEquals('usd', Cashier::usesCurrency());
        $this->assertEquals('$', Cashier::usesCurrencySymbol());
    }

    /** @test */
    public function can_optionally_set_currency_locale_in_config()
    {
        $this->assertEquals('INEXISTENT', config('cashier.currency_locale', 'INEXISTENT'));
        $this->assertEquals('de_DE', Cashier::usesCurrencyLocale());

        config(['cashier.currency_locale' => 'nl_NL']);
        $this->rebootCashierServiceProvider();

        $this->assertEquals('nl_NL', Cashier::usesCurrencyLocale());
    }

    protected function rebootCashierServiceProvider()
    {
        tap(new CashierServiceProvider($this->app))->register()->boot();
    }
}
