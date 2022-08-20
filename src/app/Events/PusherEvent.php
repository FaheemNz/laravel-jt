<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class PusherEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $data;
    protected $channel;
    protected $event;
    protected $device_token;

    public function __construct($data, $user_id)
    {
        $channel = "user_channel_".$user_id;
        $event = "user_event_".$user_id;

        $this->channel = $channel;
        $this->data = $data;
        $this->event = $event;
        $this->device_token = User::find($user_id)->device_token;

//        $this->fcmLegacyNotification();
        $this->expoNotification();
//        $this->sendOnBeam();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [$this->channel];
    }

    public function broadcastAs()
    {
        return $this->event;
    }

    private function fcmLegacyNotification(){
        $title          = $this->data['title']?: "No Title";
        $description    = $this->data['description']?: "No Description";
        $route          = env("APP_URL");
        $FCM_API_KEY    = "AIzaSyBpmfDRIAT_u0XITFKq-0ZpSCGAi_h4nxI";
        $FCM_SERVER_KEY = "AAAAu0pvgDY:APA91bHI2AD2y4j-rwydDe3A9Fv_rh_PhzjA2V_lccJQETmMygL3FPJqAIXl83wQo71fZ7o7f_yonksRGkTFM_QJnk7BsuDaMIX4iKUQpqj_KnkuD3z4HZu8ZkCQO3HCmHlDlieb7iFJ";

        $apiURL = 'https://fcm.googleapis.com/fcm/send';
        $postInput = [
            "to"        =>  $this->device_token,
            "priority"  => 'normal',
            "token"     => $FCM_API_KEY,
            "data"      => [
                "experienceId"  => '@awaiskhan128/Brrring_app',
                "scopeKey"      => '@awaiskhan128/Brrring_app',
                "title"         => $title,
                "message"       => $description,
            ],
        ];
        $headers = [
            'Content-Type'  =>  'application/json',
            'Authorization' =>  "key=$FCM_SERVER_KEY"
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $postInput);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        return $statusCode == 200? $responseBody : "Error";
    }

    private function expoNotification(){
        $title       = $this->data['title']?: "No Title";
        $description = $this->data['description']?: "No Description";
        $route       = env("APP_URL");

        $apiURL = 'https://exp.host/--/api/v2/push/send';
        $postInput = [
            "to"    =>  $this->device_token,
            "title" =>  $title,
            "body"  =>  $description
        ];

        $headers = [
            'Content-Type'  => 'application/json'
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $postInput);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        return $statusCode == 200? $responseBody : "Error";
    }

    private function sendOnBeam()
    {

        $title       = $this->data['title']?: "No Title";
        $description = $this->data['description']?: "No Description";
        $route       = env("APP_URL");

        $instance_id    = "741d0a42-dbe1-475e-8672-c3a1e3b14eeb";
        $auth_token     = "4746C308787E177C454496F90A4346AC078C6E88EE68819598CEA90089F8A4AA";
        $interests      = ['debug-hello','hello','brrring_user'];

        $apiURL = 'https://'.$instance_id.'.pushnotifications.pusher.com/publish_api/v1/instances/'.$instance_id.'/publishes';
        $postInput = [
            'interests' => $interests,
            'web'   => [
                "notification" => [
                    "title"     => $title,
                    "body"      => $description,
                    "deep_link" => $route
                ]
            ]
        ];

        $headers = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer '. $auth_token,
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $postInput);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        return $statusCode == 200? $responseBody : "Error";
    }
}
