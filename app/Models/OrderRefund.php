<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model {

    protected $table = 'order_refund';

    protected $guarded = ['id'];

    public function skus()
    {
        return $this->belongsToMany(OrderProduct::class, 'order_refund_products', 'order_refund_id', 'order_product_id')->withPivot(['order_refund_id', 'order_product_id', 'quantity', 'amount']);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
