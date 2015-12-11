<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 3/12/2015
 * Time: 5:01 PM
 */

namespace App\Services\Product\Brand;


use App\Models\Brand;
use App\Models\Category;
use App\Services\Product\Attribute\AttributeService;
use App\Services\Product\Attribute\AttributeValueService;
use Pheanstalk\Exception;

/**
 * Class BrandService
 * @package App\Services\Product\Brand
 */
class BrandService {

    /**
     * @param $name
     * @param null $cover_image
     * @return string|static
     */
    public static function create($name, $cover_image = null)
    {
        return BrandRepository::create($name, $cover_image);
    }

    /**
     * @param $id
     * @return string
     */
    public static function show($id)
    {
        return BrandRepository::show($id);
    }

    /**
     * @param $id
     * @param $data
     * - string name | require
     * - string cover_image
     * @return bool
     */
    public static function update($id, $data)
    {
        return BrandRepository::update($id, $data);
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
        return Brand::get();
    }

    /**
     * 绑定品牌与分类
     * @param $category_ids
     * @return mixed
     */
    public static function bindCategory($id, $category_ids)
    {
        $brand = Brand::find($id);
        if ( ! $brand) {
            throw new Exception('BRAND NOT FOUND');
        }
        $ids = [];
        if (is_array($category_ids)) {
            $ids = $category_ids;
        } else {
            $ids = [$category_ids];
        }
        $brand->categories()->sync($category_ids);

        return true;
    }

    /**
     * 获取某分类下所有品牌
     * @param $category_id
     * @return string
     */
    public static function getByCategory($category_id)
    {
        $cat = Category::findOrFail($category_id);
        $brands = $cat->brands()->get();

        return $brands;
    }


}
