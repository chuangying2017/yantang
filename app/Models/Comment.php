<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    //
    use SoftDeletes;

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
