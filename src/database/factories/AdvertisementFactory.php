<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Advertisement;
use Faker\Generator as Faker;

$factory->define(Advertisement::class, function (Faker $faker) {
    return [
        'name'                 => $faker->firstName,
        'description'          => $faker->realText($maxNbChars = 100, $indexSize = 2),
        'category_id'          => $faker->numberBetween(1,3),
        'url'                  => $faker->url,
        'weight'               => $faker->numberBetween(1,3),
        'quantity'             => $faker->numberBetween(10,50),
        'price'                => $faker->numberBetween(10,200),
        'currency_id'          => $faker->numberBetween(1,10),
        'reward'               => $faker->numberBetween(5,20),
        'with_box'             => $faker->boolean()
    ];
});
