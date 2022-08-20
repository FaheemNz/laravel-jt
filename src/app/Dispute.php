<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reason()
    {
        return $this->belongsTo(Reason::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function counterOffer()
    {
        return $this->belongsTo(CounterOffer::class);
    }
}
