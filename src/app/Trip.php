<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'from_city_id',
        'destination_city_id',
        'arrival_date',
        'status',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function sourceCity()
    {
        return $this->belongsTo('App\City', 'from_city_id', 'id');
    }
    public function destinationCity()
    {
        return $this->belongsTo('App\City', 'destination_city_id', 'id');
    }
    public function getCompleteSourceAddressAttribute()
    {
        if($this->sourceCity) {
            return "{$this->sourceCity->name} , {$this->sourceCity->state->country->name}";
        } else {
            return '';
        }
    }

    public function getCompleteDestinationAddressAttribute()
    {
        if($this->destinationCity) {
            return "{$this->destinationCity->name} , {$this->destinationCity->state->country->name}";
        } else {
            return '';
        }
    }
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
