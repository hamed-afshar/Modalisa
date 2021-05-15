<?php

/** @var Factory $factory */

use App\Customer;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'tel' => $faker->phoneNumber,
        'communication_media' => $faker->randomElement($array = array('Telegram', 'WhatsApp', 'Instagram')),
        'communication_id' => $faker->name,
        'address' => $faker->address,
        'email' => $faker->unique()->safeEmail
    ];
});
