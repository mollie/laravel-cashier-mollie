<?php

namespace Laravel\Cashier\Database\Factories;

use Faker\Generator as Faker;
use Laravel\Cashier\Tests\Fixtures\User;

$factory->define(User::class, fn(Faker $faker) => [
    'name' => $faker->name,
    'email' => $faker->email,
    'tax_percentage' => 0,
]);
