<?php

namespace App;

use App\Notifications\OfferNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $with = ['counterOffer'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function counterOffer()
    {
        return $this->hasOne(CounterOffer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class);
    }

    // public function reports()
    // {
    //     return $this->hasMany(Report::class,'entity_id','id');
    // }

    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class);
    }

    public static function sendNotification(Offer $offer, int $user_id, string $type, string $imageName, string $title, string $description = "")
    {
        $user = User::findOrFail($user_id);
        $user->notify(new OfferNotification($offer, $type, $imageName, $title, $description));
    }

    public static function getMappedMessages(string $type)
    {
        return
        [
            'create'    =>      'You have got a new offer on your order',
            'accept'    =>      'Your offer have been accepted',
            'reject'    =>      '',
            'updated'   =>      'Offer has been updated by the traveller'
        ][$type];
    }
}
