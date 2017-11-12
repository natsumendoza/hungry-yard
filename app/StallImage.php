<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StallImage extends Model
{
    protected $fillable = ['user_id', 'image_path'];
}
