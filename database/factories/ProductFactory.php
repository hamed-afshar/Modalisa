<?php

/** @var Factory $factory */

use App\Product;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'size' => 'X-Large',
        'color' => 'Black',
        'link' => 'www.zara.com',
        'price' => '250',
        'quantity' => '1',
        'country' => 'Turkey',
        'currency' => 'TL',
    ];
});
