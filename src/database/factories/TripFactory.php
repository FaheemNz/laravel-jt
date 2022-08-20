<?php

namespace Database\Seeders;

use App\Trip;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Trip::class, function (Faker $faker) {
    $startingDate = $faker->dateTimeThisMonth('+1 month');
    $endingDate   = date($format = 'Y-m-d', strtotime('+1 Week', $startingDate->getTimestamp()));
    #$endingDate   = '2022-05-05';
    
    return [
                'arrival_date'        => Carbon::now()->addDays(2)->toDateString(),
                'from_city_id'        => factory('App\City')->create()->id,
                'destination_city_id' => factory('App\City')->create()->id,
                'status'              => 'active',
                'user_id'             => factory('App\User')->create()->id
    ];
});
