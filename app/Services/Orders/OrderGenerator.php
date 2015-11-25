<?php namespace App\Services\Orders;
class OrderGenerator {

    public static function buy($user_id, $product_info)
    {
        $product_info = [
            'products' => [
                [
                    'product_sku_id' => 1,
                    'quantity'       => 2,
                ]
            ]
        ];
    }


    public static function prepay($user_id, $products)
    {
        #todo 检查商品详情与库存


    }

    protected static function checkProductSku($products)
    {
        foreach ($products as $key => $product) {
            
        }
    }


}
