<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ImageOfferTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offerImages = factory(App\ImageOffer::class, 5)->create();
    }
}
