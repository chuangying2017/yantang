<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDeliver extends Model {

    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'order_deliver';

    protected $guarded = ['id'];

    protected $primaryKey = 'order_id';
}
