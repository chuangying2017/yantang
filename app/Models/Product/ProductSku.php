<?php

namespace App\Models\Product;

use App\Models\Product\Traits\AttrAttribute;
use App\Models\Product\Traits\ProductSkuRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSku extends Model {

    use SoftDeletes, ProductSkuRelation, AttrAttribute;

    protected $table = 'product_skus';

    protected $guarded = ['id'];

}
