<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 1/12/2015
 * Time: 11:08 AM
 */

namespace App\Services\Product\Attribute;


use App\Models\Category;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\Attribute;

/**
 * Class AttributeService
 * @package App\Services\Product\Attribute
 */
class AttributeService
{
    /**
     * 用以管理员创建默认的属性
     * @param $name
     * @return mixed
     */
    public static function create($name)
    {
        return AttributeRepository::create($name, 0);
    }

    /**
     * 用以商家创建属性, 必须填入Merchant ID
     * @param $name
     * @param $merchant_id
     * @return static
     */
    public static function merchantCreate($name, $merchant_id)
    {
        if (!Merchant::find($merchant_id)) {
            return 'MERCHANT NOT FOUND';
        }
        return AttributeRepository::create($name, $merchant_id);
    }

    /**
     * @param $id
     * @param $name
     * @return string
     */
    public static function update($id, $name)
    {
        return AttributeRepository::update($id, $name);
    }

    /**
     * @param $id
     * @return int
     */
    public static function delete($id)
    {
        return AttributeRepository::delete($id);
    }

    /**
     * 将属性和分类绑定
     * @param integer $id
     * @param array $category_ids
     * @return mixed
     */
    public static function bindCategories($id, $category_ids)
    {
        $attr = Attribute::findOrFail($id);
        return $attr->categories()->sync($category_ids);
    }


    /**
     * 获取某分类目录下所有的属性
     * @param $category_id
     * @return mixed
     */
    public static function getByCategory($category_id)
    {
        $category = Category::findOrFail($category_id);

        return $category->attributes()->get();
    }

    /**
     * 通过名字查询属性, 如果有则返回属性, 没有就创建且返回
     * @param $name
     * @param $merchant_id
     * @return mixed
     */
    public static function findOrNew($name, $merchant_id)
    {
        return self::merchantCreate($name, $merchant_id);
    }

    /**
     * 获取商家创建的属性
     * @param $merchant_id
     * @return mixed
     */
    public static function findByMerchant($merchant_id)
    {
        return Attribute::where('merchant_id', $merchant_id)->get();
    }

    /**
     * 获取默认创建的属性
     * @return mixed
     */
    public static function getDefault()
    {
        return Attribute::where('merchant_id', 0)->get();
    }
}
