<?php

namespace App\Http\Resources;

use App\ChatRoom;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
class ChatRoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $latestMessage = ($this->messages->sortByDesc('created_at')->first()) ? $this->messages->sortByDesc('created_at')->first() : null;
        $otherPerson = ($this->traveler_id != Auth::user()->id) ? $this->traveler : $this->customer;
        if($latestMessage) {
            if($latestMessage->user_id != Auth::user()->id) {
                $otherPerson = $latestMessage->user;
            }
        }
        return [
            '_id'               => $this->id,
            'user'              => (object)[
                '_id'           => $otherPerson->id,
                "fullName"      => $otherPerson->first_name . ' ' . $otherPerson->last_name,
                'avatar'        => ($otherPerson->image) ? asset('avatars').'/'.$otherPerson->image->name : null,
                'email'         => $otherPerson->email
            ],
            'subTitle'          => ($latestMessage) ? $latestMessage->text : null,
            'seen'              => ($latestMessage) ? $latestMessage->is_seen : null,
            'productImage'      => ($this->offer->order->images->first()) ? asset('images').'/'.$this->offer->order->images->first()->name : null,
            'chatType'          => ($this->traveler_id != Auth::user()->id) ? 'TRAVELER' : 'CUSTOMER',
            'time'              => ($latestMessage) ? $latestMessage->created_at->diffForHumans() : null,
        ];
    }
}


// "http://192.168.0.244:8000/images/{\"id\":135,\"original_name\":\"image-c8ec13d4-0199-431c-a8de-bc002364af081873797512153451761.jpg\",\"name\":\"616812f5af59f1634210549_image-c8ec13d4-0199-431c-a8de-bc002364af081873797512153451761.jpg\",\"uploaded_by\":23,\"created_at\":\"2021-10-14T11:22:29.000000Z\",\"updated_at\":\"2021-10-14T11:22:29.000000Z\"}"
