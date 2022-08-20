<?php

namespace App\Http\Controllers;

use App\Country;
use App\State;
use App\User;
use App\Utills\Constants\UserRole;
use Illuminate\Http\Request;
use App\Category;
use App\Currency;
use App\City;

class SharedController extends Controller
{
    public function getAllCategories() {
        $categories = Category::all();
        return response()->json($categories);
    }
    public function getOrderAllStatuses() {
        $statuses = [
            ['id' => 'new', 'name' => 'new'],
            ['id' => 'tip', 'name' => 'tip'],
            ['id' => 'paid', 'name' => 'paid'],
            ['id' => 'purchased', 'name' => 'purchased'],
            ['id' => 'tracking', 'name' => 'tracking'],
            ['id' => 'handed', 'handed' => 'handed'],
            ['id' => 'recieved', 'name' => 'recieved'],
            ['id' => 'scanned', 'name' => 'scanned'],
            ['id' => 'traveler_rated', 'name' => 'traveler_rated'],
            ['id' => 'customer_rated', 'name' => 'customer_rated'],
            ['id' => 'rated', 'name' => 'rated'],
            ['id' => 'traveler_paid', 'name' => 'traveler_paid'],
        ];
        return response()->json($statuses);
    }
    public function getOfferAllStatuses() {
        $statuses = [
            ['id' => 'unaccepted', 'name' => 'unaccepted'],
            ['id' => 'accepted', 'name' => 'accepted'],
            ['id' => 'stale', 'name' => 'stale'],
            ['id' => 'rejected', 'name' => 'rejected'],
        ];
        return response()->json($statuses);
    }
    public function getAllCurrencies() {
        $currencies = Currency::all();
        return response()->json($currencies);
    }
    public function getAllCities(Request $request) {
        $cities = City::query()->with("state.country");
        if($request->q){
            $cities = $cities->where('name','LIKE', '%' . strtolower($request->q) . '%');
        }
        $cities = $cities->limit(10)->get()->sortBy('name')->values();
        return response()->json($cities);
    }
    public function getAllStates(Request $request)
    {
        $states = State::query()->with("country");
        if($request->q){
            $states = $states->where('name','LIKE', '%' . strtolower($request->q) . '%');
        }
        $states = $states->limit(10)->get()->sortBy('name')->values();
        return response()->json($states);
    }
    public function getAllCountries(Request $request)
    {
        $countries = Country::query();
        if($request->q){
            $countries = $countries->where('name','LIKE', '%' . strtolower($request->q) . '%');
        }
        $countries = $countries->limit(10)->get()->sortBy('name')->values();

        return response()->json($countries);
    }

    public function getAllUsers()
    {
        $users = User::where("role","<>", UserRole::ADMIN)->get(["first_name","last_name","id"]);
        return response()->json($users);
    }
}
