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
            'status' => ProductConst::VAR_PRODUCT_STATUS_UP
        ]);
    }

    /**
     * @param $id
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
        //todo@bryant add meta data
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
     */
    public static function getByCategory($category_id)
    {
        $category = Category::findOrFail($category_id);

        return $category->products()->where('status', self::PRODUCT_UP)->get();
    }

}
