<?php

namespace App\Observers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\FCMController;
use App\Message;
use App\Notification;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ChatRoomResource;

use Exception;

class MessageObserver
{
    /**
     * Handle the message "created" event.
     *
     * @param  \App\Message  $message
     * @return void
     */
    public function created(Message $message)
    {
        try {
            $requestBody = new MessageResource($message);
            $requestBody = json_encode($requestBody);
            $requestBody = json_decode($requestBody,true);
            $requestBody['chat_room_id'] = $message->chat_room_id;
            $response = Http::post(env('SOCKET_URL','http://localhost:3000').'/chatMessageCreated', $requestBody);
            \Log::info('============= Socket Raw Response Message Created ===============');
            \Log::info(json_encode($response));

            $deviceTokens = [];
            $chatRoom     = $message->chatRoom;
            $reciever     = null; 
            $sender       = null;
            if($message->user_id !== $chatRoom->traveler_id) {
                $reciever = $chatRoom->traveler;
                $sender   = $chatRoom->customer;
            } else {
                $reciever = $chatRoom->customer;
                $sender   = $chatRoom->traveler;
            }
            ($reciever) ? array_push($deviceTokens,$reciever->device_token) : '';
            
            \Log::info('============= Reciever ===============');
            \Log::info(json_encode($reciever));
            
            $entity =  [
                '_id'          => $chatRoom->id,
                'user'         => (object)[
                    '_id'        => $sender->id, 
                    "fullName"   => $sender->first_name . ' ' . $sender->last_name,
                    'avatar'     => ($sender->image) ? asset('images').'/'.$sender->image : null,
                    'email'      => $sender->email
                ],
                'subTitle'     => ($message) ? $message->text : null,
                'seen'         => ($message) ? $message->is_seen : null,
                'productImage' => ($chatRoom->offer->order->images->first()) ? asset('images').'/'.$chatRoom->offer->order->images->first()->name : null,
                'chatType'     => ($chatRoom->traveler_id != $message->user_id) ? 'TRAVELER' : 'CUSTOMER',
                'time'         => ($message) ? $message->created_at->diffForHumans() : null,
                'images'       => $chatRoom->offer->order->images
            ];
            
            \Log::info('============= Entity ===============');
            \Log::info(json_encode($entity));

            $requestBody   = [
                'device_tokens'  => $deviceTokens,
                'title'          => 'New Message',
                'body'           => substr($message->text, 0, 20).'...',
                'data'           => [
                    'entity_type' => 'Chat',
                    'entity'      => $entity,
                ]
            ];
            $requestBody = new Request($requestBody);
            $FCMController = new FCMController();
            $responseFCM = $FCMController->sendNotification($requestBody);
            \Log::info('============= FCM Raw Response Message Created===============');
            \Log::info(json_encode($responseFCM));

            // Notification for User
            $traveler_notification = Notification::create([
                'title' => $requestBody["title"],
                'description' => $requestBody["body"],
                'user_id' => $reciever->id,
                'type' => $message->user_id == $chatRoom->traveler_id? 'offer':'order',
                'body' => json_encode($requestBody['data']),
            ]);
            \Log::info('============= Notification (Traveler) ===============');
            \Log::info(json_encode($traveler_notification));

        } catch (Exception $error) {
            if(is_object($error)) {
                \Log::info(json_encode($error));
            } else {
                \Log::info($error);
            }
        }
    }

    /**
     * Handle the message "updated" event.
     *
     * @param  \App\Message  $message
     * @return void
     */
    public function updated(Message $message)
    {
        //
    }

    /**
     * Handle the message "deleted" event.
     *
     * @param  \App\Message  $message
     * @return void
     */
    public function deleted(Message $message)
    {
        //
    }

    /**
     * Handle the message "restored" event.
     *
     * @param  \App\Message  $message
     * @return void
     */
    public function restored(Message $message)
    {
        //
    }

    /**
     * Handle the message "force deleted" event.
     *
     * @param  \App\Message  $message
     * @return void
     */
    public function forceDeleted(Message $message)
    {
        //
    }
}
