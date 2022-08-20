<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Country;
use App\State;
use App\City;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LocationTableDataSeeder extends Seeder
{
    const MAX_SEED_RECORDS = 20;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::unprepared(file_get_contents('countries.sql'));
//        DB::unprepared(file_get_contents('states.sql'));
//        DB::unprepared(file_get_contents('cities.sql'));

        $countries = File::get("database/data/countries.json");
        $states = File::get("database/data/states.json");
        $cities = File::get("database/data/cities.json");

        $countries  = json_decode($countries);
        $states     = json_decode($states);
        $cities     = json_decode($cities);

        foreach($countries as $country) {
           Country::create([
                'short_code'        => $country->sortname,
                'name'              => $country->name,
                'phone_code'        => $country->phoneCode,
                'flag_url'          => "/flags/flag-of-".ucwords(str_replace(" ","-",$country->name)).".jpg"
            ]);
        }

//        foreach(array_slice($states, 0, self::MAX_SEED_RECORDS) as $state) {
        foreach($states as $state){
            if(Country::find($state->country_id)){
                State::create([
                    'id' => $state->id,
                    'name'=> $state->name,
                    'country_id'=> $state->country_id,
                ]);
            }
        }

//        foreach(array_slice($cities, 0, self::MAX_SEED_RECORDS) as $city) {
        foreach($cities as $city) {
            if(State::find($city->state_id)) {
                City::create([
                    'id' => $city->id,
                    'name' => $city->name,
                    'state_id' => $city->state_id,
                ]);
            }
        }
    }
}
