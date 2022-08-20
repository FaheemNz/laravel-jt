<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageAdvertisement extends Model
{
    protected $table = 'image_advertisement';
    
    protected $fillable = [
        'image_id',
        'advertisement_id'
    ];
}
