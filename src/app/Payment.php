<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function counterOffer()
    {
        return $this->belongsTo(CounterOffer::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, "customer_id");
    }

    public function traveller()
    {
        return $this->belongsTo(User::class, "traveler_id");
    }

    public function saveOrderPayment(Order $order)
    {
        return Payment::create([
            'user_id'       =>      auth()->id(),
            'order_id'      =>      $order->id,
            'status'        =>      'progress',
            'amount'        =>      0,
            'customer_id'   =>      $order->user_id,
            'traveler_id'   =>      $order->traveler_id,
            'type'          =>      'credit'
        ]);
    }
}
