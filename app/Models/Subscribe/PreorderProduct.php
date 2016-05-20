<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class PreorderProduct extends Model
{

    protected $guarded = ['id'];

    protected $table = 'preorder_products';

    public function preorderProductSku()
    {
        return $this->hasMany('App\Models\PreorderProductSku', 'pre_product_id');
    }
}
