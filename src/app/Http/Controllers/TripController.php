<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripRequest;
use App\Trip;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $trips = Trip::orderBy("id","desc")->paginate(10);
        return view("trip.index", compact("trips"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TripRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TripRequest $request)
    {
        Trip::create([
            'from_city_id'          => $request->from_city_id,
            'destination_city_id'   => $request->destination_city_id,
            'arrival_date'          => $request->arrival_date,
            'user_id'               => $request->user_id,
            'status'                => 'active'
        ]);

        return redirect()->route("trips.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $trip = Trip::findOrFail($id);
        return view("trip.details", compact("trip"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
