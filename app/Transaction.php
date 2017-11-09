<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable =[
        'transaction_code',
        'customer_id',
        'stall_id',
        'pickup_time',
        'total_price',
        'order_type',
        'status'
    ];

}
