<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:44 AM
 */

namespace App\Services\Product\Attribute;


    /**
     * Class AttributeValueService
     * @package App\Services\Product\Attribute
     */
/**
 * Class AttributeValueService
 * @package App\Services\Product\Attribute
 */
use App\Models\AttributeValue;

/**
 * Class AttributeValueService
 * @package App\Services\Product\Attribute
 */
class AttributeValueService
{
    /**
     * 创建属性值, 只允许管理员创建默认的
     * @param $attribute_id
     * @param $value
     * @return string|static
     */
    public static function findOrNew($attribute_id, $value)
    {
        return AttributeValueRepository::create($attribute_id, $value);
    }

    /**
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        return AttributeValueRepository::delete($id);
    }

}
