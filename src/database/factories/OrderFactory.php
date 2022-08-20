<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'name'                  =>      $faker->firstName,
        'description'           =>      $faker->realText($maxNbChars = 100, $indexSize = 2),
        'category_id'           =>      factory('App\Category')->create(),
        'url'                   =>      $faker->url,
        'weight'                =>      $faker->numberBetween(1,3),
        'quantity'              =>      $faker->numberBetween(10,50),
        'price'                 =>      $faker->numberBetween(10,200),
        'currency_id'           =>      factory('App\Currency')->create(),
        'reward'                =>      $faker->numberBetween(5,20),
        'with_box'              =>      $faker->boolean(),
        'needed_by'             =>      $faker->dateTimeBetween('now', '+1 day'),
        'from_city_id'          =>      factory('App\City')->create(),
        'destination_city_id'   =>      factory('App\City')->create(),
        'status'                =>      array_keys(Order::$statusArray)[0],
        'customer_rating'       =>      $faker->numberBetween(1,10),
        'customer_review'       =>      $faker->realText($maxNbChars = 100, $indexSize = 2),
        'traveler_rating'       =>      $faker->numberBetween(1,10),
        'traveler_review'       =>      $faker->realText($maxNbChars = 100, $indexSize = 2),
        'is_disputed'           =>      false,
        'user_id'               =>      Auth::user()->id,
        'is_doorstep_delivery'  =>      true
    ];
});
