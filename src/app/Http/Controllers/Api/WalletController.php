<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Transaction;
use App\Currency;
use App\Http\Resources\TransactionResource;
use App\Lib\Helper;

class WalletController extends BaseController
{
    public function index()
    {
        // $transactions = auth()->user()->transactions()->where('status', 'settled')->groupBy('currency_id')
        // ->selectRaw('*, sum(amount) as sum_amount')
        // ->get()
        // ->toArray();

        $all_transactions = auth()->user()->transactions()->where('status', 'settled')->get();
        $transactions = $all_transactions->toArray();
        $currency_ids = $all_transactions->unique('currency_id')->pluck('currency_id')->toArray();

        $total = 0;

        $wallets = [];
        // calculate total wallet amounts indivdually
        for ($i=0; $i <count($currency_ids) ; $i++) { 
            $currency_id = $currency_ids[$i];
            
            array_push($wallets, [
                "currency_id" => $currency_id,
                "amount" => 0,
                "currency" => Currency::find($currency_id)
            ]);

            for ($j=0; $j < count($transactions); $j++) { 
                $transaction = $transactions[$j];
                if($transaction['currency_id'] == $currency_id) {
                    $wallets[$i]['amount'] += $transaction['amount'];
                }
            }
            
            $wallets[$i]['amount_user_base'] = Helper::convertCurrency($currency_id, auth()->user()->currency->id, $wallets[$i]['amount']);
            $total += $wallets[$i]['amount_user_base'];
        }

        

        // TODO: Create resource for Transactions
        return [
            "success" => true,
            "data" => [
                "wallets" => $wallets,
                "total" => [
                    "currency" => [
                        "name" => "Total",
                        "symbol" => "PKRs"
                    ],
                    "amount" => number_format((float)$total, 2, '.', ''),
                    "amount_user_base" => number_format((float)$total, 2, '.', ''),
                ],
                "user_currency" => auth()->user()->currency
            ],
            "message" => "Success"
        ];
    }

    public function credit(Request $request)
    {
        // TODO: Validator for credit
        return Transaction::create([
            "amount" => $request->amount,
            "currency_id" => auth()->user()->currency->id,
            "source" => $request->source,
            "transaction_details" => json_encode($request->transaction_details),
            "ref_no" => $request->ref_no,
            "user_id" => auth()->user()->id
        ]);
    }

    public function debit(Request $request)
    {
        // TODO: Validator for debit

        return Transaction::create([
            "amount" => $request->amount,
            "currency_id" => $request->currency_id,
            "source" => $request->source,
            "transaction_details" => json_encode($request->transaction_details),
            "ref_no" => $request->ref_no,
            "user_id" => auth()->user()->id
        ]);
    }

    public function updateStatus(Request $request)
    {
        $ref_no = $request->ref_no;
        $transaction = Transaction::where('ref_no', $ref_no)->first();

        $transaction->status = 'settled';
        $transaction->transaction_details = json_encode($request->transaction_details);
        $transaction->save();

        return $transaction;

        // TODO: Validator for credit
        // return Transaction::create([
        //     "amount" => $request->amount,
        //     "currency_id" => auth()->user()->currency->id,
        //     "source" => $request->source,
        //     "transaction_details" => json_encode($request->transaction_details),
        //     "ref_no" => $request->ref_no,
        //     "user_id" => auth()->user()->id
        // ]);
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with('currency')->where('user_id',auth()->user()->id)->where('status','settled');

        return $this->sendResponse(
            TransactionResource::collection($query->latest()->paginate(request()->get('perPage', 10)))
            ->response()->getData(true), 'Get transactions successfully');
    }
}
