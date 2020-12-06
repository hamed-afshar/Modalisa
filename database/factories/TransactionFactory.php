<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'confirmed' => 0,
        'currency' => 'Tl',
        'amount' => '1000',
//        'image' => $faker->image(storage_path('images'), 640 , 480, null, false),
        'comment'=>'this is a test transaction'
    ];
});
