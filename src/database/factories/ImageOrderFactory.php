<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ImageOrder;
use Faker\Generator as Faker;

$factory->define(ImageOrder::class, function (Faker $faker) {
    return [
        'order_id' => $faker->numberBetween(1,10),
        'image_id' => $faker->numberBetween(1,3),
        'type'     => 'customer'
    ];
});
