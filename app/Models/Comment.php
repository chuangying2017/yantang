<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function images()
    {
        return $this->morphedByMany('App\Models\Image', 'imageable');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
