<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;

class IntegralOrderSku extends Model
{
    protected $table='integral_orders_sku';

    protected $guarded = ['id'];

    public function integral_product()
    {
        $this->belongsTo(Product::class,'product_id');
    }
}
