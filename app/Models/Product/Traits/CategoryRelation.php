<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 5/17/16
 * Time: 10:42 AM
 */

namespace App\Models\Product\Traits;


use App\Models\Product\Brand;
use App\Models\Product\Product;

trait CategoryRelation {

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_category', 'cat_id', 'product_id');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_category', 'cat_id', 'brand_id');
    }

}
