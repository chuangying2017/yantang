<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    //
    protected $table = 'product_sku_view';

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function attributeValues()
    {
        return $this->belongsToMany('App\Models\ProductSku', 'sku_attribute_value');
    }
}
