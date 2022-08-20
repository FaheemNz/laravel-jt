<?php

namespace App\Http\Controllers\Api;

use App\Events\PusherEvent;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;

class FCMController extends BaseController
{
    public function saveToken(Request $request) {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->all(),'Validation failed',400);
        }

        User::where('device_token', $request->device_token)->update([
            "device_token" => NULL
        ]);

        User::where('id', auth()->user()->id)->update([
            "device_token" => $request->device_token
        ]);

        event(new PusherEvent(['title'=>"Welcome",'description' => "Greetings From Brrring Platform"], auth()->id()));

        // auth()->user()->update(['device_token'=>$request->device_token]);
        return $this->sendResponse(
            auth()->user(),
            'Token updated successfully'
        );
    }

    public function sendNotification(Request $request) {
        //$firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->where('id',auth()->user()->id)->all();
        $firebaseToken = $request->device_tokens;
        $SERVER_API_KEY = env('FCM_SERVER_API_KEY','AAAAiAdcapA:APA91bGUDi7pxb4M7NZlkYRe1LtPcWrgCPkPC7Z3pFOw7CQ2uAk1KmtRrLCHWxTSfOG-sW-fL7CwENSOLz-OXcCFJibTOTSiCeLQKv9V3ncUKP6LQLCPOmYlG-ZEhwRexaS7dx5UxKki');

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" =>  $request->body,
            ],
            "data" => $request->data,
            "android" => [
                "priority" => "high"
            ],
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        return $this->sendResponse($response, 'FCM sent successfully');
    }
}
