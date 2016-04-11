<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {

    use SoftDeletes;

    protected $table = 'products';

    protected $guarded = ['id'];

    public function data()
    {
        return $this->hasOne('App\Models\ProductDataView', 'id', 'id');
    }

    public function meta()
    {
        return $this->hasOne('App\Models\ProductMeta', 'product_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
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

    public function skuViews()
    {
        return $this->hasMany('App\Models\ProductSkuView', 'product_id', 'id');
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

}
