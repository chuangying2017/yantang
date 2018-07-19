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
            'quantity'          =>  $data['quantity'],
            'postage'           =>  array_get($data,'postage','0'),
            'name'              =>  array_get($data,'name',null),
            'convert_num'       =>  $data['convert_num'],//兑换次数
            'convert_unit'      =>  $data['convert_unit'],//兑换单位
            'convert_day'       =>  $data['convert_day'],//兑换天数
            'bar_code'          =>  array_get($data,'bar_code',0),//商品编号
            'remainder'         =>  array_get($data,'remainder',0),//剩余量
        ]);

        return $this->next($data,$product);
    }
}