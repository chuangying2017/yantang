<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model {

    use SoftDeletes;

    protected $guarded = ['id'];

    public function products()
    {
        return $this->morphedByMany('App\Models\Product', 'imageable');
    }

    public function comments()
    {
        return $this->morphedByMany('App\Models\Comment', 'imageable');
    }
}
