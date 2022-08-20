<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageOrder extends Model
{   
    protected $table = 'image_order';

    protected $fillable = [
        'image_id',
        'order_id',
        'type'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
    
    public static function saveOrderImages(array $images, int $order_id): void {
        foreach($images as $image) {
            ImageOrder::create([
                'image_id'  =>  $image,
                'order_id'  =>  $order_id,
                'type'      =>  'customer'
            ]);
        }
    }
}
