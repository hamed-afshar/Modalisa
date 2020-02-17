<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\UserSubscription;
use Faker\Generator as Faker;

$factory->define(UserSubscription::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            return factory(App\User::class)->create()->id;
        },
        'subscription_id' => function() {
            return factory(App\Subscription::class)->create()->id;
        }
    ];
});
