<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 25/11/2015
 * Time: 11:14 AM
 */

namespace App\Services\Product;


class ProductService
{
    const PRODUCT_DOWN = 'down';
    const PRODUCT_UP = 'up';

    public static function create($data)
    {
        return ProductRepository::create($data);
    }

    public static function update($id, $data)
    {
        return ProductRepository::update($id, $data);
    }

    public static function delete($id)
    {
        return ProductRepository::delete($id);
    }

    public static function up($id)
    {
        return ProductRepository::update($id, [
            'status' => self::PRODUCT_DOWN
        ]);
    }

    public static function down($id)
    {
        return ProductRepository::update($id, [
            'status' => self::PRODUCT_DOWN
        ]);
    }

    /**
     * get an product by id
     * @param $id
     */
    public static function getById($id)
    {
        return ProductRepository::getById($id);
    }

    /**
     * get product list by category id
     * @param $category_id
     */
    public static function getByCategory($category_id)
    {

    }

    public static function getAttributes()
    {

    }
}
