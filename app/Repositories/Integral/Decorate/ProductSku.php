<?php
namespace App\Repositories\Integral\Decorate;

use App\Models\Integral\Product;
use App\Repositories\Integral\Editor\EditorAbstract;

class ProductSku extends EditorAbstract
{

    public function handle(array $data, Product $product)
    {
        $product->product_sku()->updateOrCreate(['product_id'=>$product['id']],[
            'product_id'        =>  $product['id'],
            'unit'              =>  $data['unit'],
            'quantity'          =>  $data['quantity'],
            'sales'             =>  $data['sales'],
            'postage'           =>  array_get($data,'postage','0'),
            'name'              =>  array_get($data,'name',null)
        ]);

        return $this->next($data,$product);
    }
}