<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {

    use SoftDeletes;

    protected $table = 'orders';

    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }

    public function skus()
    {
        return $this->hasMany('App\Models\OrderProductView', 'order_id', 'id');
    }

    public function address()
    {
        return $this->hasOne('App\Models\OrderAddress', 'order_id', 'id');
    }

    public function billings()
    {
        return $this->hasMany('App\Models\OrderBilling', 'order_id', 'id');
    }

    public function express()
    {
        return $this->hasOne('App\Models\OrderDeliver', 'order_id', 'id');
    }
}
