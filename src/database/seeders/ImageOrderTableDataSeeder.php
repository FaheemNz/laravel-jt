<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ImageOrderTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orderImages = factory(App\ImageOrder::class, 5)->create();
    }
}
