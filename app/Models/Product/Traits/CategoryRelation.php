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
use App\Models\Promotion\Coupon;

trait CategoryRelation {

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category', 'cat_id', 'product_id');
    }

    public function coupons()
    {
        return $this->belongsTo(Coupon::class, 'product_category', 'cat_id', 'product_id');
    }

}
