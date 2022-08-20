<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CounterOffer;
use App\Model;
use Faker\Generator as Faker;

$factory->define(CounterOffer::class, function (Faker $faker) {
    return [
        'reward'            =>  10,
        'currency_id'       =>  auth()->user()->currency->id,
        'status'            =>  'unaccepted',
        'expiry_date'       =>  '2022-05-05',
        'user_id'           =>  auth()->user()->id,
        'order_id'          =>  factory('App\Order')->create()->id,
        'trip_id'           =>  factory('App\Trip')->create()->id
    ];
});
