<?php

namespace App\Http\Controllers\Api;

use App\ChatRoom;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\BaseController;
use App\Http\Resources\ChatRoomResource;
use App\Http\Resources\MessageResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @group Chat Room
 */
class ChatRoomController extends BaseController
{

    /**
     * Get Chat Rooms
     *
     * @authenticated
     *
     * @response
     * {
     *      "success": true,
     *      "message": "Chat rooms retrieved successfully",
     *      "data"   : []
     * }
     */
    public function getChatRooms() {
        $chatRooms = ChatRoom::with(['customer.image','traveler.image','messages'])
                        ->where('is_active', true)
                        ->where(function($query){
                            $query->where('traveler_id', Auth::user()->id);
                            $query->orWhere('customer_id', Auth::user()->id);
                        })
                        ->get()
                        ->sortByDesc('messages.created_at');

        return $this->sendResponse(
            ChatRoomResource::collection($chatRooms),
            'Chat rooms retrieved successfully'
        );
    }

    /**
     * Get Chat Room Messages
     *
     * @authenticated
     *
     * @response
     * {
     *      "success": true,
     *      "message": "Chat room messages retrieved successfully",
     *      "data"   : []
     * }
     */
    public function getChatRoomMessages(int $id) {
        $chatRoom = ChatRoom::where('id', $id)->where(function ($query) {
            $query->where('traveler_id', auth()->id())
                  ->orWhere('customer_id', auth()->id());
        })->first();

        if(!$chatRoom) {
            return $this->sendError('Chat Message Error', ['No Chat Room found']);
        }

        $chatRoom->messages()->where("user_id", "<>", auth()->id())->update(["is_seen" => true]);

        $messages = $chatRoom->messages->sortBy('created_at', SORT_REGULAR, true);
        return $this->sendResponse(
            MessageResource::collection($messages),
            'Chat rooms messages retrieved successfully'
        );
    }
}
