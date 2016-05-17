<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductInfo extends Model {

    protected $table = 'product_info';

    protected $primaryKey = 'product_id';

    protected $guarded = [];
}
