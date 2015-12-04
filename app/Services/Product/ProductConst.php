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
final class ProductConst
{
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
    
}


