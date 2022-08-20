<?php

namespace App\Http\Controllers\Api;

use App\Message;
use App\Notifications\MessageNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\ChatRoom;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MessageResource;
use Validation;
use Illuminate\Validation\ValidationException;
use Auth;
class MessageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authUSer = Auth::user();

        $validator = Validator::make($request->all(), [
            'text'   => 'required|string|max:300',
            'chat_room_id'   => 'required|exists:chat_rooms,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors()->all());
        }
        $chatRoom = ChatRoom::where('id',$request->chat_room_id)->first();
        if(!$chatRoom) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'No such chat room found');
            throw new ValidationException($validator);
        }
        if($chatRoom->traveler_id != $authUSer->id && $chatRoom->customer_id != $authUSer->id){
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'Unauthorized to create message for this room');
            throw new ValidationException($validator);
        }
        if(!$chatRoom->is_active) {
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'Chat room disabled now');
            throw new ValidationException($validator);
        }

        $message = Message::create([
            'text' => $request->text,
            'user_id' => $authUSer->id,
            'chat_room_id' => $request->chat_room_id
        ]);

        $notifyUser = $chatRoom->traveler_id == $authUSer->id? $chatRoom->traveler : $chatRoom->customer;

        $notifyUser->notify(new MessageNotification($message));

        return $this->sendResponse(new MessageResource($message),'Message created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'is_seen' => 'nullable|boolean'
        ]);

        $message = Message::find($id);

        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors()->all());
        }

        if(!$message) {
            return $this->sendError('Message Error', 'No Message Exists', 200);
//            $validator = Validator::make([], []);
//            $validator->errors()->add('error', 'Message not found');
//            throw new ValidationException($validator);
        }
        if(auth()->id() == $message->user_id && $request->is_seen) {
            return $this->sendError('Message Error', 'Not authorized to update seen', 200);
//            $validator = Validator::make([], []);
//            $validator->errors()->add('error', 'Not authorized to update seen');
//            throw new ValidationException($validator);
        }
        $chatRoom = $message->chatRoom;
        if(!$chatRoom->is_active) {
            return $this->sendError('Message Error', 'Chat Not Active', 200);
//            $validator = Validator::make([], []);
//            $validator->errors()->add('error', 'Chat room disabled now');
//            throw new ValidationException($validator);
        }
        $message->is_seen = $request->is_seen;
        $message->save();
        return $this->sendResponse(new MessageResource($message),'Message deleted successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->sendError('Chat Message Error', ['Cannot Delete Message']);
//        $message = Message::where('id',$id)->first();
//        if(!$message) {
//            $validator = Validator::make([], []);
//            $validator->errors()->add('error', 'Message not found');
//            throw new ValidationException($validator);
//        }
//        $chatRoom = $message->chatRoom;
//        if(!$chatRoom->is_active) {
//            $validator = Validator::make([], []);
//            $validator->errors()->add('error', 'Chat room disabled now');
//            throw new ValidationException($validator);
//        }
//        $message->delete();
//        return $this->sendResponse(new MessageResource($message),'Message updated successfully');
    }
}
