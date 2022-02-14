<?php

namespace Laravel\Cashier\Database\Factories;

use Faker\Generator as Faker;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Tests\Fixtures\User;

$factory->define(Cashier::$redeemedCouponModel, function (Faker $faker) {
    return [
        'name' => 'Test redemeed coupon',
        'model_type' => Cashier::$subscriptionModel,
        'model_id' => 1,
        'owner_type' => User::class,
        'owner_id' => 1,
        'times_left' => 1,
    ];
});
