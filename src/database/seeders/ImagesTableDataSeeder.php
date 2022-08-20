<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Image;

class ImagesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        Image::create([
            'original_name'=> 'abc_1',
            'name'=> '/order_offer_images/1.jpg',
            'uploaded_by'=> $faker->numberBetween(2,10),
        ]);
        Image::create([
            'original_name'=> 'abc_2',
            'name'=> '/order_offer_images/2.jpg',
            'uploaded_by'=> $faker->numberBetween(2,10),
        ]);
        Image::create([
            'original_name'=> 'abc_2',
            'name'=> '/order_offer_images/3.jpg',
            'uploaded_by'=> $faker->numberBetween(2,10),
        ]);
    }
}
