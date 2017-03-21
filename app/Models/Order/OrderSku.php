<?php

namespace App\Models\Order;

use App\Models\Product\ProductSku;
use App\Models\Product\Traits\AttrAttribute;
use App\Models\Subscribe\PreorderSkuCounter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderSku extends Model {

    use SoftDeletes, AttrAttribute;

    protected $table = 'order_skus';

    protected $guarded = ['id'];

    public function counter()
    {
        return $this->hasOne(PreorderSkuCounter::class, 'order_sku_id', 'id');
    }

    public function refer()
    {
        return $this->belongsTo(OrderSku::class, 'origin_order_id', 'id');
    }

    public function refund()
    {
        return $this->hasOne(OrderSku::class, 'id', 'origin_order_id');
    }

}
