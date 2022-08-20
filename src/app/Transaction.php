<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'pkr_amount',
        'currency_id',
        'source',
        'transaction_details',
        'status',
        'ref_no',
        'user_id',
        'order_id',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
