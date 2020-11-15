<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\History;
use Faker\Generator as Faker;

$factory->define(History::class, function (Faker $faker) {
    return [
        // no need to define any property here because only history model only
        // have two fields product_id and status_id
    ];
});
