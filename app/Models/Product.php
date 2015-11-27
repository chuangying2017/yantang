<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use SoftDeletes;

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Models\Group', 'product_group');
    }

    public function images()
    {
        return $this->morphToMany('App\Models\Image', 'imageable');
    }

    public function skus()
    {
        return $this->hasMany('App\Models\ProductSku');
    }
}
