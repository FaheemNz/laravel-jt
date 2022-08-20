<?php

namespace App\Http\Controllers\ReferenceListsControllers;

use App\Bank;
use App\Http\Controllers\Controller;
use App\Utills\Constants\DefaultStatus;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $banks = Bank::paginate(1000);
        return view('reference_lists.banks', compact("banks"));
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"      => "required|string|unique:banks",
        ]);

        Bank::create([
            "name"      => $request->name,
        ]);

        return redirect()->route('banks.index');
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
     * @param  Bank  $bank
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            "name"      => "required|string|unique:banks,".$bank->id,
            "status"    => "nullable|in:". implode(",",array_keys(DefaultStatus::ALL)),
        ]);

        $bank->update([
            'name'      => $request->name,
            'status'    => $request->status ?: $bank->status
        ]);
        return redirect()->route('banks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Bank  $bank
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();
        return redirect()->route('banks.index');
    }
}
