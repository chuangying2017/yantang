<?php namespace App\Services\Orders;

use App\Services\Product\ProductSkuService;
use App\Services\Product\ProductConst;

namespace App\Services\Orders;


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
        $result = self::getProductSkuInfo(compact('product_sku_id', 'quantity'));
        if ($result['code'] != ProductConst::CODE_SKU_AFFORD_OK) {
            return $result['err_msg'];
        }

        return false;
    }

}
