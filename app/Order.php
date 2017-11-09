<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'transaction_code',
        'stall_id',
        'product_id',
        'customer_id',
        'quantity',
        'price',
        'pickup',
        'type',
        'status'
    ];

}
