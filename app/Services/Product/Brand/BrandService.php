<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 3/12/2015
 * Time: 5:01 PM
 */

namespace App\Services\Product\Brand;


use App\Services\Product\Attribute\AttributeService;
use App\Services\Product\Attribute\AttributeValueService;

/**
 * Class BrandService
 * @package App\Services\Product\Brand
 */
class BrandService
{
    /**
     *
     */
    public static function init()
    {
        return BrandRepository::init();
    }

    /**
     * @param $name
     * @param null $cover_image
     */
    public static function create($name, $cover_image = null)
    {
        return BrandRepository::create($name, $cover_image);
    }

    /**
     * @param $id
     * @param $name
     * @param null $cover_image
     * @return bool
     */
    public static function update($id, $name, $cover_image = null)
    {
        return BrandRepository::update($id, $name, $cover_image);
    }

    /**
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        return BrandRepository::delete($id);
    }

    /**
     * 获取所有品牌列表
     * @return mixed
     */
    public static function getAll()
    {
        $brandAttributeId = BrandRepository::getBrandId();
        return AttributeValueService::getByAttribute($brandAttributeId);
    }

    /**
     * 绑定品牌与分类
     * @param $category_ids
     * @return mixed
     */
    public static function bindCategory($category_ids)
    {
        $ids = [];
        if (is_array($category_ids)) {
            $ids = $category_ids;
        } else {
            $ids[] = $category_ids;
        }
        $brandAttributeId = BrandRepository::getBrandId();
        return AttributeService::bindCategories($brandAttributeId, $ids);
    }

    public static function getByCategory($category_id)
    {

    }
}
