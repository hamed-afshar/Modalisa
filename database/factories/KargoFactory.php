<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Kargo;
use Faker\Generator as Faker;

$factory->define(Kargo::class, function (Faker $faker) {
    return [
        'weight' => 120,
        'receiver_name' => 'Ramin Bey',
        'receiver_tel' => '00905332020',
        'receiver_address' => 'Turkey, Istanbul, Sisli',
        'sending_date' => $faker->date('Y-m-d')
    ];
});
