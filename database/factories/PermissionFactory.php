<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        //'name' => $faker->randomElement($array = array('create-role', 'create-user', 'assign-role'))
        'name' => 'create-role'
    ];
});
