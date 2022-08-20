<?php

namespace App\Http\Controllers;

use App\Events\PusherEvent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnnouncementController extends Controller
{
    public function index()
    {
        $users = User::whereNotNull('device_token')->get();
        return view('announcement.index', compact('users'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'users'         => "required|array|exists:users,id",
            'title'         => "required|string",
            'description'   => "required|string",
        ]);

        $users = $request->users;
        $title = $request->title;
        $description = $request->description;

        $data   = [
            'title'         => $title,
            'description'   => $description,
        ];

        $responses  = [];
        foreach ($users as $user_id){
//            event(new PusherEvent($data, $user_id));

            $title       = $data['title']?: "No Title";
            $description = $data['description']?: "No Description";
            $route       = env("APP_URL");

            $apiURL = 'https://exp.host/--/api/v2/push/send';
            $postInput = [
                "to"    =>  User::find($user_id)->device_token,
                "title" =>  $title,
                "body"  =>  $description
            ];

            $headers = [
                'Content-Type'  => 'application/json'
            ];

            $response = Http::withHeaders($headers)->post($apiURL, $postInput);

            $statusCode = $response->status();
            $responseBody = json_decode($response->getBody(), true);

            $responses[] =  $responseBody;
        }

        dd($responses);

        return redirect()->route('announcements');
    }
}
