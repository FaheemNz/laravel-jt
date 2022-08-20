<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'url',
        'weight',
        'quantity',
        'price',
        'currency_id',
        'reward',
        'with_box'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class,'image_advertisement');
    }
}
