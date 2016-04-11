<?php namespace App\Services\Orders\Helpers;

use App\Services\Product\ProductConst;
use App\Services\Product\ProductSkuService;

trait ProductsHelper {

    public static function getProductSkuInfo($products)
    {
        $products_info = ProductSkuService::afford($products);

        if ( ! count($products_info)) {
            throw new \Exception('请求商品数据错误');
        }

        $product_sku_data = [
            'success' => true,
            'message' => '',
            'data'    => []
        ];

        $products_quantity_data = self::decodeRequest($products);
        $data = [];
        foreach ($products_info as $product_info) {
            $key = $product_info['product_sku_id'];
            $sku_data = $product_info['data'];
            $data[ $key ] = array_merge(
                $sku_data->toArray(),
                [
                    'product_sku_id' => $sku_data['id'],
                    'quantity'       => $products_quantity_data[ $key ],
                    'can_buy'        => true,
                    'reason'         => null
                ]
            );

            if ( ! self::productCanAfford($product_info)) {
                $product_sku_data['success'] = false;
                $product_sku_data['message'] = $product_sku_data['message'] . ' ' . $product_info['err_msg'];
                $data[ $key ]['can_buy'] = false;
                $data[ $key ]['reason'] = $product_info['err_msg'];
            }
        }

        $product_sku_data['data'] = $data;

        return $product_sku_data;
    }

    public static function productCanAfford($product_info)
    {
        return $product_info['code'] == ProductConst::CODE_SKU_AFFORD_OK;
    }

    protected static function decodeRequest($product_request)
    {
        $data = [];
        foreach ($product_request as $key => $product) {
            $data[ $product['product_sku_id'] ] = $product['quantity'];
        }

        return $data;
    }

    public static function checkProductCanNotAfford($product_sku_id, $quantity)
    {
        $result = self::getProductSkuInfo([compact('product_sku_id', 'quantity')]);

        if ( ! $result['success']) {
            return $result['message'];
        }

        return false;
    }

}
