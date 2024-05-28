<?php

declare(strict_types=1);

namespace Laravel\Cashier\Tests\Mollie;

use Laravel\Cashier\Tests\BaseTestCase;

abstract class BaseMollieInteraction extends BaseTestCase
{
    protected $interactWithMollieAPI = true;
}
