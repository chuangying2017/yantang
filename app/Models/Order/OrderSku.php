<?php

namespace App\Models\Order;

use App\Models\Product\ProductSku;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderSku extends Model {

    use SoftDeletes;

    protected $table = 'order_products';

    protected $guarded = ['id'];

    public function sku()
    {
        return $this->hasOne(ProductSku::class, 'id', 'product_sku_id');
    }

}
