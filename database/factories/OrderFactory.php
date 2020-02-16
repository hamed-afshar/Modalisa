<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'id' => $faker->numberBetween($min = 3000, $max = 4000),
        'user_id' => function() {
            return factory(App\User::class)->create()->id;
        },
        'created_at' => $faker->dateTimeThisMonth, 
        'country' => 'Turkey',
        'updated_at' => $faker->dateTimeThisMonth
    ];
});
