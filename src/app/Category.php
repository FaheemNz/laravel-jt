<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    
    protected $fillable = [
        'name','image_url','tariff'
    ];

    public function orders()
    {
       return $this->hasMany(Order::class);
    }

    public function advertisements()
    {
       return $this->hasMany(Advertisement::class);
    }
}
