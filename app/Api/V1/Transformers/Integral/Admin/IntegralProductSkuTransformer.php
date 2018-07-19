<?php

namespace App\Api\V1\Transformers\Integral\Admin;

use App\App\Models\Integral\ProductSku;
use League\Fractal\TransformerAbstract;

class IntegralProductSkuTransformer extends TransformerAbstract {

    public function transform(ProductSku $productSku)
    {
            return [
                'id'=>$productSku['id'],
                'product_id'=>$productSku['product_id'],
                'quantity'=>$productSku['quantity'],
                'sales'=>$productSku['sales'],
                'postage'=>$productSku['postage'],
                'name'=>$productSku['name'],
                'convert_num'=>$productSku['convert_num'],//兑换次数
                'convert_unit'=>$productSku['convert_unit'],//兑换单位
                'convert_day'=>$productSku['convert_day'],//兑换天数
            ];
    }
}
