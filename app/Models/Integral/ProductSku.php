<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $table='integral_product_sku';

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    protected $casts = ['specification' => 'json'];

    public function integral_product()
    {
       return $this->belongsTo(Product::class,'product_id');
    }
}
