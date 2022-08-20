<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;

class HelperController extends Controller
{
    public static function convertCurrency($from,$to,$amount){
        $result = null;
        $fromCurrency = Currency::find($from);
        $toCurrency = Currency::find($to);
        
        if($fromCurrency && $toCurrency) {
            if($fromCurrency->rate > 0) {        
                $baseCurrency = $amount / $fromCurrency->rate;
                $toReqCurreny = $baseCurrency * $toCurrency->rate;
                $result = $toReqCurreny;
            } else {
                $result = $amount;
            }
        }
        
        if($result > 0 ) {
            $result = round($result, 2);
        }
        
        return $result;
    }
}
