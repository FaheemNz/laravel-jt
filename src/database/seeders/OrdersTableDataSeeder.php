<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Order;

class OrdersTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = [
            [
                'id'                                        => 1,
                'thumbnail'                                 => "broken.jpg",
                'name'                                      => "HP Laptop",
                'description'                               => "HP 255 G8 - AMD Ryzen 3 3300U 08GB 01-TB HDD AMD Radeon Vega 6 Graphics 15.6\" HD 720p Narrow Border AG Display TPM 2.0 W11 (Grey, Open box)",
                'category_id'                               => 7,
                'url'                                       => 'https://www.paklap.pk/hp-255-g8-amd-ryzen-3-laptop-price-pakistan.html',
                'quantity'                                  => 1,
                'weight'                                    => 1,
                'with_box'                                  => 0,
                'is_doorstep_delivery'                      => 1,
                'currency_id'                               => 1,
                'price'                                     => 79000,
                'reward'                                    => 1000,
                'from_city_id'                              => 5909,
                'destination_city_id'                       => 5934,
                'user_id'                                   => 2,
                'needed_by'                                 => now()->addDays(10),
                'traveler_service_charges_percentage'       =>  0.03,
                'customer_service_charges_percentage'       =>  0.05,
                'customer_duty_charges_percentage'          =>  0.20,
            ],
            [
                'id'                                        => 2,
                'thumbnail'                                 => "broken.jpg",
                'name'                                      => "Apple MacBook Pro 13",
                'description'                               => "2 i7-8559U 16GB RAM 512GB SSD Touch Bar - Silver (Renewed)",
                'category_id'                               => 7,
                'url'                                       => 'https://www.amazon.co.uk/Mid-2018-Apple-MacBook-i7-8559U-512GB/dp/B09FQB67ZS',
                'quantity'                                  => 1,
                'weight'                                    => 1,
                'with_box'                                  => 1,
                'is_doorstep_delivery'                      => 0,
                'currency_id'                               => 1,
                'price'                                     => 120000,
                'reward'                                    => 2000,
                'from_city_id'                              => 5909,
                'destination_city_id'                       => 5934,
                'user_id'                                   => 3,
                'needed_by'                                 => now()->addDays(20),
                'traveler_service_charges_percentage'       =>  0.03,
                'customer_service_charges_percentage'       =>  0.05,
                'customer_duty_charges_percentage'          =>  0.20,
            ]
        ];

        foreach ($orders as $order){
            Order::create($order);
        }
    }
}
