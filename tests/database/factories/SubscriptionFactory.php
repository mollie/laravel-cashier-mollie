<?php

namespace Laravel\Cashier\Database\Factories;

use Carbon\Carbon;
use Faker\Generator as Faker;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Tests\Fixtures\User;

$factory->define(Cashier::$subscriptionModel, fn(Faker $faker) => [
    'name' => 'dummy name',
    'plan' => 'monthly-10-1',
    'owner_id' => 1,
    'owner_type' => User::class,
    'cycle_started_at' => now(),
    'cycle_ends_at' => fn(array $subscription) => Carbon::parse($subscription['cycle_started_at'])->addMonth(),
    'tax_percentage' => 0,
]);
