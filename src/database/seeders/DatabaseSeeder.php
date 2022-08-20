<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SystemSettingTableSeeder::class);
        $this->call(CategoriesTableDataSeeder::class);
        $this->call(LocationTableDataSeeder::class);
        $this->call(CurrenciesTableDataSeeder::class);

        $this->call(UsersTableDataSeeder::class);


        $this->call(ReasonsTableSeeder::class);

        $this->call(TripsTableDataSeeder::class);
        $this->call(OrdersTableDataSeeder::class);
        $this->call(OffersTableDataSeeder::class);




        // Not To UnComment This
//        $this->call(ImagesTableDataSeeder::class);
//        $this->call(ImageOrderTableDataSeeder::class);
//        $this->call(ImageOfferTableDataSeeder::class);
//        $this->call(AdvertisementsTableDataSeeder::class);
//        $this->call(TransactionSeeder::class);
        Artisan::call('passport:install');
    }
}
