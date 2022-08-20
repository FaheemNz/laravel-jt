<?php

namespace Database\Factories;

use App\Reason;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

$factory->define(Reason::class, function (Faker $faker) {
    return [
        'description'      => $faker->realText($maxNbChars = 100, $indexSize = 2)
    ];
});
