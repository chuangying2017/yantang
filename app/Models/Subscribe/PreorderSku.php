<?php namespace App\Models\Subscribe;

use App\Models\Product\ProductSku;
use Illuminate\Database\Eloquent\Model;

class PreorderSku extends Model
{
    protected $guarded = ['id'];

    protected $table = 'preorder_skus';

    public function sku()
    {
        return $this->hasOne(ProductSku::class, 'id', 'product_sku_id');
    }

}
