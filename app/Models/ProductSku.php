<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSku extends Model
{
    use SoftDeletes;

    protected $table = 'product_sku';

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function attributeValues()
    {
        return $this->belongsToMany('App\Models\AttributeValue', 'sku_attribute_value');
    }
}
