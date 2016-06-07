<?php

namespace App\Models\Order;


use App\Models\Order\Traits\OrderRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {

    use SoftDeletes, OrderRelation;

    protected $table = 'orders';

    protected $guarded = ['id'];



}
