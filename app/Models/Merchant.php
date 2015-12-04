<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchant extends Model
{
    use SoftDeletes;

    public function images()
    {
        return $this->morphToMany('App\Models\Image', 'imageable');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
