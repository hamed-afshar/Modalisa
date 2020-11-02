<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Transactions;
use Faker\Generator as Faker;

$factory->define(Transactions::class, function (Faker $faker) {
    return [
        'currency' => 'Tl',
        'amount' => '1000',
        'pic' => 'link',
        'comment'=>'this is a test transaction'
    ];
});
