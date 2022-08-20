<?php

namespace Database\Seeders;

use App\Trip;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TripsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trips = [
            [
                'id'                    => 1,
                'from_city_id'          => 5909,
                'destination_city_id'   => 5934,
                'arrival_date'          => now()->addDays(8),
                'user_id'               => 4,
            ],
            [
                'id'                    => 2,
                'from_city_id'          => 5909,
                'destination_city_id'   => 5934,
                'arrival_date'          => now()->addDays(15),
                'user_id'               => 4,
            ],
        ];

        foreach ($trips as $trip){
            Trip::create($trip);
        }
    }
}
