<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    //
    protected $table = 'product_sku';

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
