<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable =[
        'transaction_code',
        'customer_id',
        'stall_id',
        'preparation_time',
        'pickup_time',
        'total_price',
        'order_type',
        'status',
        'paymaya_receipt_number',
        'paymaya_transaction_reference_number',
        'customer_view',
        'stall_view'
    ];

}
