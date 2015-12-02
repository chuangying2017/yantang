<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 25/11/2015
 * Time: 11:14 AM
 */

namespace App\Services\Product;


use App\Models\Category;
use App\Services\Product\Attribute\AttributeService;

/**
 * Class ProductService
 * @package App\Services\Product
 */
class ProductService
{
    /**
     *
     */
    const PRODUCT_DOWN = 'down';
    /**
     *
     */
    const PRODUCT_UP = 'up';

    /**
     * @param $data
     * @return bool|string
     */
    public static function create($data)
    {
        return ProductRepository::create($data);
    }

    /**
     * @param $id
     * @param $data
     */
    public static function update($id, $data)
    {
        return ProductRepository::update($id, $data);
    }

    /**
     * @param $id
     */
    public static function delete($id)
    {
        return ProductRepository::delete($id);
    }

    /**
     * @param $id
     */
    public static function up($id)
    {
        return ProductRepository::update($id, [
            'status' => self::PRODUCT_DOWN
        ]);
    }

    /**
     * @param $id
     */
    public static function down($id)
    {
        return ProductRepository::update($id, [
            'status' => self::PRODUCT_DOWN
        ]);
    }

    /**
     * get an product by id
     * @param $id
     * @return array
     */
    public static function getById($id)
    {
        $data = [];
        $product = Product::findOrFail($id);
        $data['basic_info'] = $product;
        $data['images'] = $product->images()->get();
        $data['groups'] = $product->groups()->get();
        $data['comments'] = $product->comments()->get();
        $data['attributes'] = AttributeService::getByProduct($id);
        $data['skus'] = ProductSkuService::getByProduct($id);

        return $data;
    }

    /**
     * get product list by category id
     * @param $category_id
     */
    public static function getByCategory($category_id)
    {
        $category = Category::findOrFail($category_id);
        return $category->products()->where('status', self::PRODUCT_UP)->get();
    }

}
