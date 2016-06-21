<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class PreorderProductSku extends Model
{

    protected $guarded = ['id'];

    protected $table = 'preorder_product_sku';

    public function preorderProduct()
    {
        return $this->belongsTo('App\Models\PreorderProduct', 'pre_product_id');
    }
}
