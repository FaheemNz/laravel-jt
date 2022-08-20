<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Offer;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;

$factory->define(Offer::class, function (Faker $faker) {
    return [
        'description'      => $faker->realText($maxNbChars = 100, $indexSize = 2),
        'status'           => 'unaccepted',
        'price'            => $faker->numberBetween(500,1000),
        'currency_id'      => $faker->numberBetween(1,10),
        'reward'           => $faker->numberBetween(1,10),
        'service_charges'  => $faker->numberBetween(1,10),
        'expiry_date'      => $faker->date($format = 'Y-m-d', $max = 'now'),
        'trip_id'          => $faker->numberBetween(1,10),
        'order_id'         => 1,
        'user_id'          => 1
    ];
});
