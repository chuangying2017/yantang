<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 25/11/2015
 * Time: 11:44 AM
 */

namespace App\Services\Product;

use App\Models\ProductSku;

class ProductSkuService
{
    public static function create($data, $product_id)
    {
        return ProductSkuRepository::create($data, $product_id);
    }

    public static function update($id, $data)
    {
        return ProductSkuRepository::update($id, $data);
    }

    public static function getAttributes($product_sku_id)
    {
        $attributes = [];
        $product_sku = ProductSku::firstOrFail($product_sku_id);
        $attributeValues = $product_sku->attributeValues()->get();
        foreach ($attributeValues as $value) {
            $attribute = Attribute::findOrFail($value->attribute_id);
        }
    }

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
