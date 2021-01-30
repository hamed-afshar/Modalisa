<?php

/** @var Factory $factory */

use App\Status;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Status::class, function (Faker $faker) {
    return [
        'priority' => '1',
        'name' => 'submitted',
        'description' => 'Just entered into the system'
    ];
});
