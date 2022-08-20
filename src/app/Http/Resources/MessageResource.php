<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            '_id'        => $this->id,
            'user'      => (object)[
                '_id'       => $this->user->id,
                'fullName' => $this->user->first_name . ' ' . $this->user->last_name,
                'avatar'   => ($this->user->image) ? asset('avatars').'/'.$this->user->image->name : null,
                'email'    => $this->user->email
            ],
            'is_seen'   => $this->is_seen,
            'text'      => $this->text,
            'createdAt' => $this->created_at,
            'sent'      => true,
            'received'  => true
        ];
        if(Auth::user()->id == $this->user->id) {
            $data['is_seen']   = true;
        }
        return $data;
    }
}
