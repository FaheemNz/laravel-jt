<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Offer;

class OffersTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offers = [
            [
                'id'               => 1,
                'price'            => 80000,
                'reward'           => 500,
                'description'      => "I can bring this laptop for you. But its little expensive over here.",
                'service_charges'  => 4000,
                'expiry_date'      => now()->addDay(2),
                'currency_id'      => 1,
                'trip_id'          => 1,
                'order_id'         => 1,
                'user_id'          => 4,
            ],
            [
                'id'               => 2,
                'price'            => 110000,
                'reward'           => 10000,
                'description'      => "I can bring this laptop cheaper but need more reward",
                'service_charges'  => 4000,
                'expiry_date'      => now()->addDay(1),
                'currency_id'      => 1,
                'trip_id'          => 2,
                'order_id'         => 2,
                'user_id'          => 4,
            ]
        ];


        foreach ($offers as $offer){
            Offer::create($offer);
        }
    }
}
