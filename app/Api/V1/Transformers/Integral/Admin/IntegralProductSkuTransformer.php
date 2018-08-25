<?php

namespace App\Api\V1\Transformers\Integral\Admin;


use App\Models\Integral\ProductSku;
use League\Fractal\TransformerAbstract;

class IntegralProductSkuTransformer extends TransformerAbstract {

    public function transform(ProductSku $productSku)
    {
            return [
                'id'                =>$productSku['id'],
                'bar_code'          =>$productSku['bar_code'],//商品编码
                'product_id'        =>$productSku['product_id'],
                'quantity'          =>$productSku['quantity'],
                'sales'             =>$productSku['sales'],
                'postage'           =>$productSku['postage'],
                'name'              =>$productSku['name'],
                'convert_num'       =>$productSku['convert_num'],//兑换次数
                'convert_unit'      =>$productSku['convert_unit'],//兑换单位
                'convert_day'       =>$productSku['convert_day'],//兑换天数
                'remainder'         =>$productSku['remainder'],
                'browse_num'        =>$productSku['browse_num']
            ];
    }
}
