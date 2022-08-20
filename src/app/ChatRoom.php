<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = [
        'offer_id','is_active','customer_id','traveler_id'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function traveler()
    {
        return $this->belongsTo(User::class,'traveler_id','id');
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
