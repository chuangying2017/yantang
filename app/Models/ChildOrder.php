<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildOrder extends Model {

    protected $table = 'child_orders';

    protected $guarded = ['id'];

    public function skus()
    {
        return $this->hasMany('App\Models\OrderProduct', 'child_order_id');
    }

    public function address()
    {
        return $this->hasOne('App\Models\OrderAddress', 'order_id', 'order_id');
    }

    public function deliver()
    {
        return $this->hasOne('App\Models\OrderDeliver', 'id', 'deliver_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }

}
