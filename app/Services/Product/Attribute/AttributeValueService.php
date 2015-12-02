<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:44 AM
 */

namespace App\Services\Product\Attribute;


class AttributeValueService
{
    public static function firstOrCreate($attribute_id, $value, $merchat_id)
    {
        return AttributeValueRepository::firstOrCreate($attribute_id, $value, $merchat_id);
    }

    /**
     * get values under a attribute
     * @param $attribute_id
     * @return mixed
     */
    public static function getByAttribute($attribute_id)
    {
        $attribute = Attribute::findOrFail($attribute_id);

        return $attribute->values()->get();
    }
}
