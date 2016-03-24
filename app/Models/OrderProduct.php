<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use SoftDeletes;
    protected $table = 'order_products';

    protected $guarded = ['id'];

    public function product()
    {
        return $this->hasOne(ProductSku::class, 'id', 'product_sku_id');
    }

}
