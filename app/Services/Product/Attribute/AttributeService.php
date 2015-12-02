<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 1/12/2015
 * Time: 11:08 AM
 */

namespace App\Services\Product\Attribute;


class AttributeService
{
    public static function create($name)
    {
        return AttributeRepository::create($name);
    }

    public static function update($id, $name)
    {
        return AttributeRepository::update($id, $name);
    }

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
}
