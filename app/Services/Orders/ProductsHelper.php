<?php namespace App\Services\Orders;


namespace App\Services\Orders;


use App\Services\Product\ProductConst;
use App\Services\Product\ProductSkuService;

trait ProductsHelper {

    public static function getProductSkuInfo($products)
    {
        return ProductSkuService::afford($products);
    }

    public static function productCanAfford($product_info)
    {
        return $product_info['code'] == ProductConst::CODE_SKU_AFFORD_OK;
    }

    public static function checkProductCanNotAfford($product_sku_id, $quantity)
    {
        try {
            $result = reset(self::getProductSkuInfo([compact('product_sku_id', 'quantity')]));
            if ($result['code'] != ProductConst::CODE_SKU_AFFORD_OK) {
                return $result['err_msg'];
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return false;
    }

}
