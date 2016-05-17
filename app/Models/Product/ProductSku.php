<?php

namespace App\Models\Product;

use App\Models\Product\Traits\ProductSkuRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSku extends Model {

    use SoftDeletes, ProductSkuRelation;

    protected $table = 'product_sku';

    protected $guarded = ['id'];

}
