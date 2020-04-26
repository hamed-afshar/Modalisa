<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Subscription;
use Faker\Generator as Faker;

$factory->define(Subscription::class, function (Faker $faker) {
    return [
        'plan' => $faker->unique()->randomElement($array = array('Gold', 'Silver', 'Bronze', 'test')),
        'cost_percentage' => $faker->randomElement($array = array(0, 10, 20, 30))
    ];
});
