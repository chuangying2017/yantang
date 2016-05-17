<?php
/**
 * Created by PhpStorm.
 * User: troy
 * Date: 5/17/16
 * Time: 10:58 AM
 */

namespace App\Models\Product\Traits;


use App\Models\Product\AttributeValue;
use App\Models\Product\Product;
use App\Models\Product\ProductSku;

trait ProductSkuRelation {

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'sku_attribute_value', 'product_sku_id', 'attr_value_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function mix()
    {
        return $this->belongsToMany(ProductSku::class, 'product_mix_sku', 'product_sku_id', 'product_sku_mix_id');
    }


}
