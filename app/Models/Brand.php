<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model {

    use SoftDeletes;

    protected $table = 'brands';

    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'brand_category');
    }
}
