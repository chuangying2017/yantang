<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 1/12/2015
 * Time: 11:08 AM
 */

namespace App\Services\Product\Attribute;


use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\Attribute;

class AttributeService
{
    /**
     * @param $name
     * @return mixed
     */
    public static function create($name)
    {
        return AttributeRepository::create($name);
    }

    /**
     * @param $id
     * @param $name
     */
    public static function update($id, $name)
    {
        return AttributeRepository::update($id, $name);
    }

    /**
     * @param $id
     */
    public static function delete($id)
    {
        return AttributeRepository::delete($id);
    }

    /**
     * bind an attribute with categories
     * @param $id
     * @param $category_ids
     * @return mixed
     */
    public static function bindCategories($id, $category_ids)
    {
        $attr = Attribute::findOrFail($id);
        return $attr->categoeies()->sync($category_ids);
    }


    /**
     * get all attributes by category
     * @param $category_id
     * @return mixed
     */
    public static function getByCategory($category_id)
    {
        $category = Category::findOrFail($category_id);

        return $category->attributes()->get();
    }

    /**
     * @param $product_id
     * @return array
     */
    public static function getByProduct($product_id)
    {
        $product = Product::findOrFail($product_id);
        $skus = $product->skus()->get();
        $attribute_values = [];
        foreach ($skus as $sku) {
            $attribute_values = array_merge($attribute_values, ProductSku::find($sku->id)->attributeValues()->get());
        }
        return $attribute_values;
    }
}
