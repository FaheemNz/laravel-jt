<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'entity_id',
        'type',
        'reason',
        'description',
        'is_reviewed',
        'is_resolved',
        'admin_review'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'entity_id');
    }

    public function offer()
    {
        return $this->belongsTo(Order::class,'entity_id');
    }
}
