<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'id',
        'name',
        'country_id'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
