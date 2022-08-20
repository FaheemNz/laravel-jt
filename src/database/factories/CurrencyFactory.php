<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Currency;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Currency::class, function (Faker $faker) {
    return [
        'name' => $faker->currencyCode,
        'short_code' => $faker->currencyCode,
        'symbol' => '$',
        'rate' => $faker->numberBetween(1, 100),
        'country_id' => 1
    ];
});
