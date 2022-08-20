<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Currency;
use Illuminate\Support\Facades\File;

class CurrenciesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies_file = File::get("database/data/currencies.json");
        $currencies_json = json_decode($currencies_file,true);
        $currency_arr_keys = array_keys($currencies_json);

        foreach($currency_arr_keys as $currency_obj) {
            $currency = $currencies_json[$currency_obj];

            Currency::create([
                'name'=> $currency['name'],
                'short_code'=> $currency['code'],
                'symbol'=> $currency['symbol'],
                'rate'  => 0,
                'country_id' => 1
            ]);
        }
    }
}
