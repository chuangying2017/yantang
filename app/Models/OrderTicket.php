<?php

namespace App\Models;

use App\Models\Order\Order;
use App\Models\Order\OrderSku;
use Illuminate\Database\Eloquent\Model;

class OrderTicket extends Model {

    protected $table = 'order_tickets';

    protected $guarded = ['id'];

    public function exchange()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function skus()
    {
        return $this->hasMany(OrderSku::class, 'order_id', 'order_id');
    }



}
