<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '123456789', // password
        'remember_token' => Str::random(10),
        'confirmed' => 0,
        'last_login' => $faker->dateTimeThisMonth,
        'locked' => 0,
        'last_ip' => $faker->ipv4,
        'language' => 'Persian',
        'tel' => $faker->phoneNumber,
        'country' => 'Iran',
        'communication_media' => $faker->randomElement($array = array('Telegram', 'WhatsApp', 'Instagram'))
    ];
});
