<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Currency;
use App\Http\Resources\UserCurrencyResource;
use App\Lib\Helper;
use Illuminate\Http\Request;

/**
 *
 * Currency
 *
 * @group Currency
 */
class CurrencyController extends BaseController
{
    /**
     * Display a listing of all the Currencies.
     *
     * @bodyParam name string Name of the Currency. If no name is provided, all the currencies will be returned
     *
     * @response
     * {
     *   "data": {
     *   "success": true,
     *      "data": [
     *          {
     *              "id": 1,
     *              "name": "Currency1",
     *              "short_code": "Cur1",
     *              "symbol": "C1",
     *              "flag_url": null,
     *              "rate": 100
     *          }
     *      ]
     *  },
     *  "message": "Currencies retrieved successfully"
     * }
     */
    public function index()
    {
        $name = request()->input('name');

        if($name != "") {
            return $this->sendResponse(
                UserCurrencyResource::collection(
                    Currency::with('country')
                        ->where('name', 'LIKE', '%'.strtolower($name).'%')
                        ->orWhere('short_code', 'LIKE', '%'.strtolower($name).'%')
                        ->orWhere('symbol', 'LIKE', '%'.strtolower($name).'%')
                        ->get()
                )
                ->response()
                ->getData(true),
                'Currencies retrieved successfully');
        }

        return $this->sendResponse(
            UserCurrencyResource::collection(
                Currency::with('country')
                    ->latest()
                    ->get()
            )
            ->response()
            ->getData(true),
            'Currencies retrieved successfully'
        );
    }

    /**
     * Show a single Currency
     *
     *  @urlParam id required ID of the Currency
     */
    public function show(int $id)
    {
        $currency = Currency::with('country')
            ->where('id', $id)
            ->get();

        if( count($currency) < 1 ){
            return $this->sendError('Invalid currency ID', ['No such Currency Found'], 400);
        }

        return $this->sendResponse(
            UserCurrencyResource::collection($currency)
                ->response()
                ->getData(true),
            'Currency retrieved successfully'
        );
    }

    public function convertAmount(Request $request)
    {
        if(isset($request->from_id, $request->to_id, $request->amount)){
            $from   = Currency::find($request->from_id);
            $to     = Currency::find($request->to_id);
            $amount = $request->amount;

            if($from && $to){
                $new_amount = Helper::convertCurrency($from->id, $to->id, $amount);

                $response_data = [
                    'from'      => $from,
                    'to'        => $to,
                    'amount'    => $new_amount
                ];
                return $this->sendResponse($response_data, "Currency Converted Successfully");
            }else{
                return $this->sendError("Currency Does not exists");
            }
        }else{
            return $this->sendError("Some parameters are missing");
        }
    }
}
