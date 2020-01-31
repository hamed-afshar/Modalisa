<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'orderID' => $faker->numberBetween($min = 3000, $max = 4000),
        'users_id' => function() {
            return factory(App\User::class)->create()->id;
        },
        'Status_statusID' => '1',
        'created_at' => $faker->dateTimeThisMonth, 
        'country' => 'Turkey',
        'updated_at' => $faker->dateTimeThisMonth
    ];
});
