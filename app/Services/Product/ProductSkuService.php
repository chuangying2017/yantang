<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 25/11/2015
 * Time: 11:44 AM
 */

namespace App\Services\Product;

class ProductSkuService
{
    public static function afford($queryArr = array())
    {

        return array(
            array(
                "code" => "", //ProductConst::CODE_SKU_AFFROD_OK | ProductConst::CODE_SKU_AFFROD_ERR,
                "err_msg" => "",
                "product_sku_id" => "",
                "data" => array(
                    array(
                        "category_id" => "",
                        "merchant_id" => "",
                        "product_sku_id" => "",
                        "cover_image" => "",
                        "title" => "",
                        "price" => "",
                        "member_discount" => "",
                        "stock" => "",
                        "attributes" => array(
                            array(
                                "name" => "",
                                "value" => ""
                            ),
                            array(
                                "name" => "",
                                "value" => ""
                            )
                        )
                    )
                )
            )
        );
    }
}
