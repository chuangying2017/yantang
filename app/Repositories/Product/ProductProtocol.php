<?php namespace App\Repositories\Product;
final class ProductProtocol {

    const DEFAULT_MERCHANT_ID = 0;

    const PRODUCT_PER_PAGE = 20;

    const TYPE_OF_MIX = 'mix';
    const TYPE_OF_ENTITY = 'entity';
    const TYPE_OF_SERVICE = 'service';
    const TYPE_OF_VIRTUAL = 'virtual';
    const TYPE_OF_SUBSCRIBE = 'subscribe';

    /**
     *
     */
    const CODE_SKU_NOT_AFFORD = "4001";
    const CODE_SKU_AFFORD_OK = "2000";
    /**
     *
     */
    const MSG_SKU_NOT_AFFORD = "商品库存不足";
    const MSG_SKU_AFFORD_OK = "sku afford ok";

    const VAR_PRODUCT_STATUS_UP = 'up'; //上架, 在售
    const VAR_PRODUCT_STATUS_DOWN = 'down'; // 下架
    const VAR_PRODUCT_STATUS_SELLOUT = 'sellout'; // 售罄

    const VAR_PRODUCT_OPEN_STATUS_NOW = 'now'; // 立马开售
    const VAR_PRODUCT_OPEN_STATUS_FIXED = 'fixed'; // 固定时间开售

    public static function groups($group_id = null)
    {
        $data = [
            1 => '国际品牌',
            2 => '国货精品'
        ];


    }

    public static function saleStatus($status = null, $key = false)
    {
        $data = [
            self::VAR_PRODUCT_STATUS_UP => '在售',
            self::VAR_PRODUCT_STATUS_DOWN => '下架',
            self::VAR_PRODUCT_STATUS_SELLOUT => '售罄',
        ];

        if ($key) {
            return isset($data[$status]) ? $status : self::VAR_PRODUCT_STATUS_UP;
        }

        return is_null($key) ? array_get($data, $key, null) : $data;
    }


    public static function openStatus($key = null, $find_key = false)
    {
        $data = [
            self::VAR_PRODUCT_OPEN_STATUS_NOW => '马上开售',
            self::VAR_PRODUCT_OPEN_STATUS_FIXED => '定时开售'
        ];

        if ($find_key) {
            return isset($data[$key]) ? $key : self::VAR_PRODUCT_OPEN_STATUS_NOW;
        }

        return is_null($key) ? array_get($data, $key, null) : $data;
    }


    public static function getProductSortOption($order_by)
    {
        $options = ['created_at', 'price'];

        return in_array($order_by, $options) ? $order_by : false;
    }


}
