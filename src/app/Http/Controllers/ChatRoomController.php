<?php

namespace App\Http\Controllers;

use App\ChatRoom;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{

    public function index()
    {
        $chats = ChatRoom::all();
        return view("chat.inbox", compact('chats'));
    }

    public function fetchAllMessagesForChat(ChatRoom $chatRoom)
    {
        $messages = $chatRoom->messages;
        return response()->json([
            "data" => (object)[
                "messages"      => $messages,
                "traveller"     => $chatRoom->traveler,
                "customer"      => $chatRoom->customer
            ]
        ], 200);
    }
}
