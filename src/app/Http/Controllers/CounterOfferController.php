<?php

namespace App\Http\Controllers;

use App\CounterOffer;
use Illuminate\Http\Request;

class CounterOfferController extends Controller
{
    public function index()
    {
        $counter_offers = CounterOffer::paginate(10);
        return view("counter_offer.index", compact("counter_offers"));
    }
//    public function store(Request $request, int $id)
//    {
//        CounterOffer::all();
//    }
}
