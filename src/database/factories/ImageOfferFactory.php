<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ImageOffer;
use Faker\Generator as Faker;

$factory->define(ImageOffer::class, function (Faker $faker) {
    return [
        'offer_id' => $faker->numberBetween(1,10),
        'image_id' => $faker->numberBetween(1,10),
    ];
});
