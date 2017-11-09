<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'stall_id',
        'name',
        'image',
        'price',
        'preparation_time'
    ];

}
