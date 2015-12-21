<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 25/11/2015
 * Time: 11:14 AM
 */

namespace App\Services\Product;


use App\Models\Category;
use App\Models\Product;
use App\Services\Product\Category\CategoryService;

/**
 * Class ProductService
 * @package App\Services\Product
 */
class ProductService {

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
     * @return int
     */
    public static function delete($id)
    {
        return ProductRepository::delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function up($id)
    {
        return ProductRepository::updateStatus($id, ProductConst::VAR_PRODUCT_STATUS_UP);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function down($id)
    {
        return ProductRepository::update($id, ProductConst::VAR_PRODUCT_STATUS_DOWN);
    }

    public static function sellOut($id)
    {
        return ProductRepository::update($id, ProductConst::VAR_PRODUCT_STATUS_SELLOUT);
    }

    /**
     * get an product by id
     * @param $id
     * @return array
     */
    public static function show($id)
    {
        $product = Product::with('data', 'images', 'groups', 'skuViews', 'meta')->findOrFail($id);

        return $product;
    }

    public static function lists($category_id = null, $brand_id = null, $paginate = null, $orderBy = null, $orderType = 'desc', $status = null)
    {
        $orderBy = ProductConst::getSortOption($orderBy);
        $orderType = ProductConst::getSortType($orderBy);
        $brand_id = ! is_null($brand_id) ? to_array($brand_id) : null;
        $status = ProductConst::saleStatus($status, true);
        $category_ids = CategoryService::getLeavesId($category_id);

        $products = ProductRepository::lists($category_ids, $brand_id, $paginate, $orderBy, $orderType, $status);

        return $products;
    }

    /**
     * get product list by category id
     * @param $category_id
     * @param int $page
     * @param string $order_by
     * @param string $sort
     * @return
     */
    public static function getByCategory($category_id, $page = 1, $order_by = "id", $sort = 'asc')
    {
        $category = Category::findOrFail($category_id);

        return $category->products()->where('status', ProductConst::VAR_PRODUCT_STATUS_UP)->orderBy($order_by, $sort)->paginate($page);
    }

    /**
     * @param int $page
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public static function getAll($page = 1, $order_by = "id", $sort = 'asc')
    {
        return Product::orderBy($order_by, $sort)->paginate($page);
    }

}
