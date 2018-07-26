<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;

class IntegralOrderSku extends Model
{
    protected $table='integral_orders_sku';

    protected $guarded = ['id'];

    protected $casts = ['specification' => 'json'];

    public function integral_product()
    {
       return $this->belongsTo(Product::class,'product_id');
    }

    public function integral_order()
    {
       return $this->belongsTo(IntegralOrder::class,'order_id','id');
    }
}
