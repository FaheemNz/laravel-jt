<?php

namespace App;

use App\Notifications\CounterOfferNotification;
use Illuminate\Database\Eloquent\Model;

class CounterOffer extends Model
{
    protected $table = 'counter_offer';
    protected $guarded = [];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function reason()
    {
        return $this->hasOne(Reason::class);
    }

    public static function sendNotification(CounterOffer $counterOffer, int $user_id, string $type, string $orderImageName, string $title, string $description = "")
    {
        $user = User::findOrFail($user_id);
        $user->notify(new CounterOfferNotification($counterOffer, $type, $orderImageName, $title, $description));
    }

    public static function getMappedMessages(string $type)
    {
        return
        [
            'create'        =>      'A New Counter Offer has been created',
            'accept'        =>      'Your Counter Offer have been accepted',
            'reject'        =>      ''
        ][$type];
    }
}
