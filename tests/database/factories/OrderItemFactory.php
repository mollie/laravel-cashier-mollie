<?php

namespace Laravel\Cashier\Database\Factories;

use Faker\Generator as Faker;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Tests\Fixtures\User;

$factory->define(Cashier::$orderItemModel, function (Faker $faker) {
    return [
        'owner_type' => User::class,
        'owner_id' => 1,
        'orderable_type' => Cashier::$subscriptionModel,
        'orderable_id' => 1,
        'description' => 'Some dummy description',
        'unit_price' => 12150,
        'quantity' => 1,
        'tax_percentage' => 21.5,
        'currency' => 'EUR',
        'process_at' => now()->subMinute(),
    ];
});

$factory->state(Cashier::$orderItemModel, 'unlinked', [
    'orderable_type' => null,
    'orderable_id' => null,
]);

$factory->state(Cashier::$orderItemModel, 'unprocessed', [
    'order_id' => null,
]);

$factory->state(Cashier::$orderItemModel, 'processed', [
    'order_id' => 1,
]);

$factory->state(Cashier::$orderItemModel, 'EUR', [
    'currency' => 'EUR',
]);

$factory->state(Cashier::$orderItemModel, 'USD', [
    'currency' => 'USD',
]);
