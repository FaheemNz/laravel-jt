<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Advertisement;

class AdvertisementsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $advertisements = factory(App\Advertisement::class, 15)->create();
    }
}
