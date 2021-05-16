<?php

/** @var Factory $factory */

use App\Note;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Note::class, function (Faker $faker) {
    return [
        'title' => 'this is a note title',
        'body' => 'this is a note body'
    ];
});
