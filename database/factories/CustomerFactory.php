<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name' => 'Shadi Rahbari',
        'tel' => '09121111111',
        'communication_media' => 'Telegram',
        'communication_id' => 'shadi_rahbari',
        'address' => 'Niyavaran St',
        'email' => 'customer@yahoo.com'
    ];
});
