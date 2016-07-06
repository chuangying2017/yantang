<?php

namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class PreorderSkuCounter extends Model {

    protected $table = 'preorder_sku_counter';

    protected $guarded = [];

    protected $primaryKey = 'order_sku_id';

    public $incrementing = false;

}
