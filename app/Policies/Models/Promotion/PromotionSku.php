<?php

namespace App\Models\Promotion;

use App\Models\Product\ProductSku;
use Illuminate\Database\Eloquent\Model;

class PromotionSku extends Model {

    protected $table = 'promotion_skus';

    protected $guarded = ['id'];

    public function sku()
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id', 'id');
    }
}
