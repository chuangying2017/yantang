<?php
namespace App\Services\Product\Brand;

/*
 * Created by PhpStorm.*
 * User: Bryant
 * Date: 3/12/2015
 * Time: 5:00 PM
 */
use App\Services\Product\Attribute\AttributeService;
use App\Services\Product\Attribute\AttributeValueService;

/**
 * Class BrandRepository
 * @package App\Services\Product\Brand
 */
class BrandRepository
{
    /**
     *
     */
    public static function init()
    {
        AttributeService::create('brand');
    }

    /**
     * 获取brand的attribute id
     */
    public static function getBrandId()
    {
        $attribute = AttributeService::findByName('brand');
        return $attribute->id;
    }

    /**
     * @param $name
     * @param null $cover_image
     */
    public static function create($name, $cover_image = null)
    {

        AttributeValueService::firstOrCreate(self::getBrandId(), $name, $cover_image);
    }

    /**
     * @param $id
     * @param $name
     * @param null $cover_image
     * @return bool
     */
    public static function update($id, $name, $cover_image = null)
    {
        return AttributeValueService::update($id, [
            'value' => $name,
            'cover_image' => $cover_image
        ]);
    }

    /**
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        return AttributeValueService::delete($id);
    }
}
