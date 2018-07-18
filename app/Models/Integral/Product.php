<?php

namespace App\Models\Integral;

use App\App\Models\Integral\ProductSku;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table='integral_product';
    //
    protected $guarded = ['id'];

    public function scopeStatus($query, $type)
    {
        return  $query->where('status',$type);
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable','integral_imageables');
    }

    public function integral_category()
    {
        return $this->belongsToMany(IntegralCategory::class,'integral_product_cate','product_id','category_id');
    }

    public function product_sku()
    {
        return $this->hasOne(ProductSku::class);
    }
}
