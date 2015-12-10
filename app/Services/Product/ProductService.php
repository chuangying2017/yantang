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
use App\Models\ProductDataView;
use App\Models\ProductMeta;
use App\Services\Product\Attribute\AttributeService;

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
        return ProductRepository::update($id, [
            'status' => ProductConst::VAR_PRODUCT_STATUS_UP
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function down($id)
    {
        return ProductRepository::update($id, [
            'status' => ProductConst::VAR_PRODUCT_STATUS_DOWN
        ]);
    }

    public static function sellOut($id)
    {
        return ProductRepository::update($id, [
            'status' => ProductConst::VAR_PRODUCT_STATUS_SELLOUT
        ]);
    }

    /**
     * get an product by id
     * @param $id
     * @return array
     */
    public static function show($id)
    {
        $data = [];
        $product = Product::findOrFail($id);
        $data['basic_info'] = $product;
        $data['data'] = ProductDataView::find($id);
        $data['images'] = $product->images()->get();
        $data['groups'] = $product->groups()->get();
        $data['skus'] = ProductSkuService::getByProduct($id);
        $data['meta'] = ProductMeta::where('product_id', $id)->first();

        return $data;
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

        return $category->products()->where('status', self::PRODUCT_UP)->orderBy($order_by, $sort)->paginate($page);
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
