<?php

namespace App\Http\Controllers\Api;

use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\UserCityResource;

class CityController extends BaseController
{
    public function index()
    {
        $city_name = request()->input('name');

        if($city_name != "") {
            return $this->sendResponse(
                UserCityResource::collection(City::with('state.country')->where('name', 'like', '%'.strtolower($city_name).'%')->orderBy('name')->paginate(request()->get('perPage', 10)))
                ->response()->getData(true),
                 'Get cities successfully');
        } else {

            return $this->sendResponse(
                UserCityResource::collection(City::with('state.country')->orderBy('name')->paginate(request()->get('perPage', 10)))
                ->response()->getData(true),
                 'Get cities successfully');
        }
    }
}
