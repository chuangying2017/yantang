<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
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
        return $this->belongsToMany('App\Models\Image', 'product_image');
    }

    public function skus()
    {
        return $this->hasMany('App\Models\ProductSku');
    }
}
