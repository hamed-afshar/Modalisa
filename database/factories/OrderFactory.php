<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'orderID' => $faker->numberBetween($min = 3000, $max = 4000),
        'Users_id' => '3',
        'Satus_statusID' => '1',
        'created_at' => $faker->dateTime,
        'country' => 'Turkey'
    ];
});
