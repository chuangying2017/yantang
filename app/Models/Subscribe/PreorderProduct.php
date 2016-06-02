<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class PreorderProduct extends Model
{

    protected $guarded = ['id'];

    protected $table = 'preorder_products';

    public function sku()
    {
        return $this->hasMany('App\Models\Subscribe\PreorderProductSku', 'pre_product_id');
    }
}
