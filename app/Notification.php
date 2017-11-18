<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'from',
        'to',
        'action',
        'read_flag'
    ];
}
