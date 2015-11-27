<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    public function products()
    {
        return $this->morphedByMany('App\Models\Product', 'imageable');
    }

    public function comments()
    {
        return $this->morphedByMany('App\Models\Comment', 'imageable');
    }
}
