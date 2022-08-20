<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageOffer extends Model
{
    protected $table = 'image_offer';
    
    protected $fillable = [
        'image_id', 'offer_id',
    ];
}
