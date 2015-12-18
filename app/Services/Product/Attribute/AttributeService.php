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
class AttributeService {

    /**
     * 用以管理员创建默认的属性
     * @param $name
     * @return mixed
     */
    public static function create($name, $merchant_id = 0)
    {
        if ($merchant_id) {
            return self::merchantCreate($name, $merchant_id);
        }

        return AttributeRepository::create($name, $merchant_id);
    }

    /**
     * 用以商家创建属性, 必须填入Merchant ID
     * @param $name
     * @param $merchant_id
     * @return static
     */
    protected static function merchantCreate($name, $merchant_id)
    {
        $merchant = Merchant::findOrFail($merchant_id);

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
    public static function getByCategory($category_id, $merchant_id = 0)
    {
        $category = Category::find($category_id);
        if ( ! $category) {
            return [];
        }

        return $category->attributes()->whereIn('merchant_id', AttributeRepository::transformMerchantId($merchant_id))->get();
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
        return AttributeRepository::lists($merchant_id);
    }

    /**
     * 获取商家创建的属性
     * @param $merchant_id
     * @return mixed
     */
    public static function findAllByMerchant($merchant_id)
    {
        return AttributeRepository::lists($merchant_id);
    }

    /**
     * 获取默认创建的属性
     * @return mixed
     */
    public static function getDefault()
    {
        return AttributeRepository::lists();
    }
}
