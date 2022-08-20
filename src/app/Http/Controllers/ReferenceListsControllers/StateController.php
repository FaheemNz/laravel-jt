<?php

namespace App\Http\Controllers\ReferenceListsControllers;

use App\Http\Controllers\Controller;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $states = State::paginate(1000);
        return view('reference_lists.states', compact("states"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"          => "required|string",
            "country_id"    => "required|integer|exists:countries,id"
        ]);

        State::create([
            "name"          => $request->name,
            "country_id"    => $request->country_id,
        ]);

        return redirect()->route('state.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  State  $state
     * @return Response
     */
    public function destroy(State $state)
    {
        $state->delete();
        return redirect()->route('state.index');
    }
}
