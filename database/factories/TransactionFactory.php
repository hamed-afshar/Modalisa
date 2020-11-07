<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'confirmed' => 0,
        'currency' => 'Tl',
        'amount' => '1000',
        'pic' => 'link',
        'comment'=>'this is a test transaction'
    ];
});
