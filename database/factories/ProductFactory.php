<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'size' => 'X-Large',
        'color' => 'Black',
        'pic' => 'pic link',
        'link' => 'www.zara.com',
        'price' => '250',
        'quantity' => '1',
        'weight' => '200',
        'country' => 'Turkey',
        'currency' => 'TL',
        'ref' => '11200300'
    ];
});
