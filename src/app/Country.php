<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'short_code',
        'phone_code',
        'flag_url'
    ];

    public function states()
    {
        return $this->hasMany(State::class);
    }
    public function currency()
    {
        return $this->hasOne(Currency::class);
    }
}
