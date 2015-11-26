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
    /**
     * get an product by id
     * @param $id
     */
    public static function getById($id)
    {
        $product = Product::find($id);
        $skus = $product->skus()->get();

    }

    /**
     * get product list by category id
     * @param $category_id
     */
    public static function getByCategory($category_id)
    {

    }
}
