<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        /**
         * do not need any property because this model only contains
         * user_id and customer_id
         */
    ];
});
