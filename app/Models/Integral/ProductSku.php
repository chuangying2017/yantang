<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $table='integral_product_sku';

    protected $guarded = ['id'];

    public function integral_product()
    {
        $this->belongsTo(Product::class,'product_id');
    }
}
