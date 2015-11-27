<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    protected $table = 'orders';

    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }

    public function address()
    {
        return $this->hasMany('App\Models\OrderAddress', 'order_id', 'id');
    }

    public function billings()
    {
        return $this->hasMany('App\Models\OrderBilling', 'order_id', 'id');
    }
}
