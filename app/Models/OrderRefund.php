<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model {

    protected $table = 'order_refund';

    protected $guarded = ['id'];

    public function products()
    {
        return $this->belongsToMany(OrderProduct::class, 'order_refund_products', 'order_refund_id', 'order_product_id')->withPivot(['quantity', 'amount']);
    }

}
