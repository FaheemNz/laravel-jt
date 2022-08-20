<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'original_name',
        'name',
        'uploaded_by'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }

    public function advertisements()
    {
        return $this->belongsToMany(Advertisement::class,'image_advertisement');
    }

    public function users()
    {
        return $this->hasMany(Order::class);
    }

    public function imageOrder()
    {
        return $this->hasOne(ImageOrder::class);
    }
}
