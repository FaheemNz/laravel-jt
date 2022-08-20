<?php

namespace App\Http\Controllers\ReferenceListsControllers;

use App\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $currencies = Currency::all();
        return view('reference_lists.currencies', compact("currencies"));
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
            "name"        => "required|string",
            "short_code"  => "required|string",
            "symbol"      => "required|string",
            "rate"        => "required|numeric|gt:0.1",
            "country_id"  => "required|exists:countries,id",
        ]);

        Currency::create([
            "name"              => $request->name,
            "short_code"        => $request->short_code,
            "symbol"            => $request->symbol,
            "rate"              => $request->rate,
            "country_id"        => $request->country_id,
        ]);

        return redirect()->route('currency.index');
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
     * @param  Currency  $currency
     * @return RedirectResponse
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();
        return redirect()->route('currency.index');
    }
}
