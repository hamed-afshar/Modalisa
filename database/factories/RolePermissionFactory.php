<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\RolePermission;
use Faker\Generator as Faker;

$factory->define(RolePermission::class, function (Faker $faker) {
    return [
        'role_id' => function () {
            return factory(App\Role::class)->create()->id;
        },
        'permission_id' => function() {
            return factory(App\Permission::class)->create()->id;
    }
    ];
});