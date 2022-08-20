<?php

namespace App\Lib;

use App\Currency;
use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Helper
{
    public static function getExceptionInfo($exception)
    {
        return
        [
            'message'   =>     $exception->getMessage(),
            'line'      =>     $exception->getLine(),
            'trace'     =>     $exception->getTraceAsString(),
            'file'      =>     $exception->getFile(),
            'message'   =>     $exception->getMessage(),
        ];
    }

    public static function dummyValidator(string $message)
    {
        $validator = Validator::make([], []);
        $validator->errors()->add('error', $message);

        return $validator;
    }

    public static function sendSuccessJsonResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }

    public static function sendErrorJsonResponse($error, $errorMessages = [], $code = 400)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public static function send_mail($to, $subject, $body) {
        try {
            Mail::raw($body, function($message) use ($to, $subject, $body) {
                $message->to($to);
                $message->subject($subject);
                $message->setBody($body, 'text/html');
                $message->bcc('it@example.com');
            });

            if (Mail::failures()) {
                return -1;
            }

            return 1;
        } catch (\Exception $exception) {
            static::log('### Helper - Mail Exception ###', static::getExceptionInfo($exception));
            return -1;
        }
    }

    public static function log($ident, $msg)
    {
        Log::info($ident, [
            'PATH' => request()->path(),
            'MSG' => str_replace("\n", "", var_export($msg, true)),
            'UNIQUE_ID' => isset($_SERVER['UNIQUE_ID']) ? $_SERVER['UNIQUE_ID'] : ''
        ]);
    }

    public static function convertCurrency(int $from, int $to, $amount){
        $fromCurrency = Currency::find($from);
        $toCurrency = Currency::find($to);

        $result = null;

        if($fromCurrency && $toCurrency) {
            if($fromCurrency->rate > 0) {
                $baseCurrency = $amount / $fromCurrency->rate;
                $toReqCurreny = $baseCurrency * $toCurrency->rate;
                $result = $toReqCurreny;
            } else {
                $result = $amount;
            }
        }

        if($result > 0) {
            $result = round($result, 2);
        }

        return $result;
    }

    public static function getUniqueImageName($imageName)
    {
        return Str::snake(uniqid() . '_' . time() . '_' . $imageName);
    }

    private static function GenerateRandomNo($length, $splitAfter)
    {
        return implode('-',str_split(strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length)),$splitAfter));
    }

    public static function generatePinCode($length=6, $splitAfter=3){
        do {
            $random_order_number = self::GenerateRandomNo($length, $splitAfter);
            $already_exist       = Order::where('pin_code', $random_order_number)->first();
        } while (!is_null($already_exist));

        return 	$random_order_number;
    }

    public static function removeSpecialChFromString($string){
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        return Str::snake($string);
    }

    public static function deStructureNiftToken($token)
    {
        $token = preg_split('/\,/',  hex2bin($token));
        $arr = [];
        foreach ($token as $item){
            $i = preg_split('/\=/', $item);
            if($i[0] == "time"){
                $arr[$i[0]] =  Carbon::parse($i[1])->diffInMinutes(now());
            }else{
                $arr[$i[0]] = $i[1];
            }

        }
        return (object) $arr;
    }


}
