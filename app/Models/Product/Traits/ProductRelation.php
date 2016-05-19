<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 5/17/16
 * Time: 10:09 AM
 */

namespace App\Models\Product\Traits;


use App\Models\Product\Brand;
use App\Models\Merchant;
use App\Models\Product\Category;
use App\Models\Product\ProductInfo;
use App\Models\Product\ProductSku;
use App\Models\Product\ProductMeta;
use App\Repositories\Category\CategoryProtocol;

trait ProductRelation {

    public function meta()
    {
        return $this->hasOne(ProductMeta::class);
    }

    public function info()
    {
        return $this->hasOne(ProductInfo::class);
    }

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function cats()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'cat_id')->withPivot('type')->wherePivot('type', CategoryProtocol::TYPE_OF_MAIN);
    }

    public function groups()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'cat_id')->withPivot('type')->wherePivot('type', CategoryProtocol::TYPE_OF_GROUP);
    }

}
