<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildOrder extends Model {

    protected $table = 'child_orders';

    protected $guarded = ['id'];

    public function skus()
    {
        return $this->hasMany('App\Models\OrderProductView', 'child_order_id', 'id');
    }

    public function address()
    {
        return $this->hasOne('App\Models\OrderAddress', 'order_id', 'order_id');
    }

    public function express()
    {
        return $this->hasOne('App\Models\OrderDeliver', 'child_order_id', 'id');
    }

}
