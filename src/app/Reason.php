<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function counterOffer()
    {
        return $this->belongsTo(CounterOffer::class);
    }
    
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
