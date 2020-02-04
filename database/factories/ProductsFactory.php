<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Products;
use Faker\Generator as Faker;

$factory->define(Products::class, function (Faker $faker) {
    return [
        'size' => $faker->randomElement($array = array('Small', 'Medium', 'Large', 'X-Large')),
        'link' => 'www.modalisa.org',
        'price' => $faker->numberBetween($min = 20, $max = 500),
        'delivery_cost' => $faker->numberBetween($min = 5, $max = 10),
        'picture_link' => $faker->imageUrl($width, $height, $category, $randomize),
        'color' => $faker->randomElement($array = array('Red', 'Black', 'White', 'Blue', 'Yellow', 'Green')),
        'quantity' => $faker->numberBetween($min = 1, $max = 10)
    ];
});
