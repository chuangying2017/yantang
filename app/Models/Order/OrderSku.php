<?php

namespace App\Models\Order;

use App\Models\Product\ProductSku;
use App\Models\Product\Traits\AttrAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderSku extends Model {

    use SoftDeletes, AttrAttribute;

    protected $table = 'order_skus';

    protected $guarded = ['id'];



}
