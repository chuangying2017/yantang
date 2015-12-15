<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 25/11/2015
 * Time: 11:50 AM
 */

namespace App\Services\Product;


/**
 * Class ProductConst
 * @package App\Services\Product
 */
final class ProductConst {

    /**
     *
     */
    const CODE_SKU_NOT_AFFORD = "4001";
    const CODE_SKU_AFFORD_OK = "2000";
    /**
     *
     */
    const MSG_SKU_NOT_AFFORD = "sku not afford";
    const MSG_SKU_AFFORD_OK = "sku afford ok";

    const VAR_PRODUCT_STATUS_UP = 'up'; //上架, 在售
    const VAR_PRODUCT_STATUS_DOWN = 'down'; // 下架
    const VAR_PRODUCT_STATUS_SELLOUT = 'sellout'; // 售罄

    const VAR_PRODUCT_OPEN_STATUS_NOW = 'now'; // 立马开售
    const VAR_PRODUCT_OPEN_STATUS_FIXED = 'fixed'; // 固定时间开售

    public static function saleStatus($status = null, $key = false)
    {
        $data = [
            self::VAR_PRODUCT_STATUS_UP      => '在售',
            self::VAR_PRODUCT_STATUS_DOWN    => '下架',
            self::VAR_PRODUCT_STATUS_SELLOUT => '售罄',
        ];

        if ($key) {
            return isset($data[ $status ]) ? $status : self::VAR_PRODUCT_STATUS_UP;
        }

        return is_null($key) ? array_get($data, $key, null) : $data;
    }


    public static function openStatus($key = null, $find_key = false)
    {
        $data = [
            self::VAR_PRODUCT_OPEN_STATUS_NOW   => '马上开售',
            self::VAR_PRODUCT_OPEN_STATUS_FIXED => '定时开售'
        ];

        if ($find_key) {
            return isset($data[ $key ]) ? $key : self::VAR_PRODUCT_OPEN_STATUS_NOW;
        }

        return is_null($key) ? array_get($data, $key, null) : $data;
    }


    public static function getSortOption($order_by)
    {
        $available = ['sales', 'price'];

        return is_null($order_by) ? null : array_get($available, $order_by, 'price');
    }

    public static function getSortType($type)
    {
        $available = ['desc', 'asc'];

        return array_get($available, $type, 'desc');
    }

}


