<?php

namespace App\Http\Controllers\Api;

use App\Bank;
use App\BankAccount;
use App\Http\Controllers\BaseController;
use App\Utills\Constants\DefaultStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends BaseController
{

    public function banks()
    {
        $banks = \App\Bank::where("status", DefaultStatus::ACTIVE)->get();
        return $this->sendResponse(
            $banks,
            'Banks Loaded Successfully',
            200
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bankAccounts = auth()->user()->bankAccounts;
        return $this->sendResponse(
            $bankAccounts,
            'Bank Accounts Loaded Successfully',
            200
        );
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "bank_id"                   => "required|exists:banks,id",
            "account_title"             => "required|string",
            "account_number"            => "required|numeric|unique:bank_accounts,account_number"
        ], [
            'bank_id.required'          =>  'A valid bank is required',
            'bank_id.exists'            =>  'Bank does not exists in system',
            'account_title.required'    =>  'Account title/holder name is required',
            'account_number.required'   =>  'Account Number is required',
            'account_number.unique'     =>  'Account Number already exists',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Bank Account Error', $validator->errors());
        }

        $bankAccount = BankAccount::create([
            "user_id"               => auth()->id(),
            "bank_id"               => $request->bank_id,
            "account_title"         => $request->account_title,
            "account_number"        => $request->account_number,
        ]);

        return $this->sendResponse(
            $bankAccount,
            'Account Added Successfully'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $bankAccount = BankAccount::find($id);

        if(!$bankAccount){
            return $this->sendError("Bank Account Error", ["Invalid Account Provided"]);
        }

        $validator = Validator::make($request->all(), [
            "bank_id"                   => "required|exists:banks,id",
            "account_title"             => "required|string",
            "account_number"            => "required|numeric|unique:bank_accounts,account_number,".$bankAccount->id
        ], [
            'bank_id.required'          =>  'A valid bank is required',
            'bank_id.exists'            =>  'Bank does not exists in system',
            'account_title.required'    =>  'Account title/holder name is required',
            'account_number.required'   =>  'Account Number is required',
            'account_number.unique'     =>  'Account Number already exists',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Bank Account Error', $validator->errors());
        }

        $bankAccount->update([
            "bank_id"               => $request->bank_id,
            "account_title"         => $request->account_title,
            "account_number"        => $request->account_number,
        ]);

        return $this->sendResponse(
            $bankAccount,
            'Account Updated Successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BankAccount $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();
        return $this->sendResponse(
            ['Deleted'],
            'Account Deleted Successfully'
        );
    }
}
