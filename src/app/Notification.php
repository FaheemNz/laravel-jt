<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'id' => 'string'
    ];
    
    public function getCreatedAtAttribute(string $time)
    {
        return Carbon::parse($time)->diffForHumans();
    }
    public function getUpdatedAtAttribute(string $time)
    {
        return Carbon::parse($time)->diffForHumans();
    }
}
