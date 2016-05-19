<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 3:58 PM
 */

namespace App\Services\Product;

use App\Http\Requests\Request;
use App\Models\Product\Product;
use App\Models\Product\ProductMeta;
use App\Models\Product\ProductSku;
use App\Repositories\Product\ProductProtocol;
use App\Services\Product\Comment\CommentService;
use App\Services\Product\Fav\FavService;
use App\Services\Product\Search\Facades\ProductSearch;
use App\Services\Product\Tags\ProductTagService;
use DB;
use Exception;
use XSTokenizerScws;

/**
 * Class ProductRepository
 * @package App\Services\Product
 */
class ProductRepository {


    public static function lists($category_id = null, $brand_id = null, $paginate = 20, $orderBy = null, $orderType = 'desc', $status = ProductProtocol::VAR_PRODUCT_STATUS_UP, $keyword = null, $new_query = true)
    {
        $query = Product::with('meta', 'data')->where('status', $status);

        if ( ! is_null($category_id)) {
            $query = $query->whereIn('category_id', $category_id);
        }

        if ( ! is_null($brand_id)) {
            $query = $query->whereIn('brand_id', $brand_id);
        }


        if ( ! is_null($keyword)) {

            $limit = 50;
            $search = ProductSearch::getSearch();
            $result = $search->setQuery($keyword)->setLimit($limit)->search();
            $result_count = $search->getLastCount();
            $product_ids = [];
            if ($result_count > 0) {
                foreach ($result as $result_value) {
                    $product_ids[] = $result_value['id'];
                }
                for ($i = 1; $i <= $result_count / $limit + 1; $i++) {
                    $result = $search->setQuery($keyword)->setLimit($limit, $limit * $i)->search();
                    foreach ($result as $result_value) {
                        $product_ids[] = $result_value['id'];
                    }
                }
            }
            $query = $query->whereIn('id', $product_ids);

//            $query = $query->where(function ($query) use ($keyword) {
//                $query->where('title', 'like', '%' . $keyword . '%')->orWhereHas('meta', function ($query) use ($keyword) {
//                    return $query->where('tags', 'like', '%' . $keyword . '%');
//                });
//            });
        }


        if ( ! is_null($orderBy)) {
            if (ProductProtocol::getProductSortOption($orderBy)) {
                $query = $query->orderBy($orderBy, $orderType);
            } else if ($orderBy == 'sales' || $orderBy == 'favs' || $orderBy == 'stock') {
                $query = $query->join('product_data_view', 'product_data_view.id', '=', 'products.id')
                    ->orderBy('product_data_view.' . $orderBy, $orderType);
            }
        } else {
            $query = $query->orderBy('category_id')->orderBy('product_no');
        }


        if ( ! is_null($paginate)) {
            $products = $query->paginate($paginate);
        } else {
            $products = $query->get();
        }


        return $products;
    }

    private static function decodePrice($data)
    {
        $data['price'] = store_price($data['price']);
        $data['origin_price'] = store_price($data['origin_price']);
        $data['express_fee'] = store_price($data['express_fee']);

        return $data;
    }


    /**
     * filter data for database
     * @param $data
     * @return mixed
     */
    private static function filterBasicData($data)
    {
        $rules = [
            'brand_id', 'category_id', 'merchant_id', 'title', 'sub_title',
            'price', 'origin_price', 'limit', 'member_discount', 'digest',
            'cover_image', 'open_status', 'open_time', 'stock'
        ];

        return array_only($data, $rules);
    }

    private static function filterMetaData($data)
    {
        $rules = [
            'detail', 'is_virtual', 'origin_id', 'express_fee', 'attributes', 'with_invoice', 'with_care', 'tags'
        ];

        return array_only($data, $rules);
    }

    /**
     * product create process
     * @param $data
     * @return bool|string
     * @throws Exception
     * @structure
     *  - (array) basic_info
     *      - (string) product_no: 商品编码, 后端生成
     *      - *(integer) brand_id
     *      - *(integer) category_id
     *      - *(integer) merchant_id
     *      - *(string) title
     *      - (string) sub_title
     *      - *(integer) price
     *      - (integer) origin_price
     *      - (integer) limit = 0
     *      - (integer) member_discount = 0
     *      - (string) digest
     *      - (string) cover_image
     *      - (string) status =
     *      - *(string) open_status
     *      - (timestamp) open_time
     *      - *(text) attributes
     *             - [name, id, values[[name,id]]
     *
     *      - *(text) detail
     *      - (bool) is_virtual = 0
     *      - (string) origin_id
     *      - (integer) express_fee = 0
     *      - (bool) with_invoice
     *      - (bool) with_care
     *  - *(array) skus
     *      - {
     * - "name": "sku-1",
     * - "cover_image": "11231",
     * - "stock": 10,
     * - "price": 10000,
     *  - "attribute_value_ids": []
     * }
     * ],
     *  - (array) image_ids
     *  - (array) group_ids
     */
    public static function create($data)
    {
        try {
            DB::beginTransaction();

            $data = $data['data'];

            $product = self::touchProduct($data);

            /**
             * create skus
             */
            $skus = ProductSkuService::create($data['skus']['data'], $product->id);
            $product->skus = $skus;
            /**
             * link group
             */
            $product->groups()->sync(array_get($data, 'group_ids', []));
            /**
             * link image
             */
            $product->images()->sync(array_get($data, 'image_ids', []));


            $product->brand()->increment('product_count', 1);

            $product->category()->increment('product_count', 1);


            DB::commit();

            return $product;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * update a product
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public static function update($id, $data)
    {

        try {
            DB::beginTransaction();
            $data = $data['data'];
            /**
             * filter data for security
             */
            $product = self::touchProduct($data, $id);
            /**
             * update skus
             */
            ProductSkuService::sync($data['skus']['data'], $product);
            /**
             * link group
             */
            $product->groups()->sync($data['group_ids']);
            /**
             * link image
             */
            $product->images()->sync($data['image_ids']);


            DB::commit();

            return $product;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public static function updateStatus($ids, $status)
    {
        $ids = to_array($ids);

        return Product::whereIn('id', $ids)->update(['status' => $status]);
    }


    /**
     * @param $data
     * @param null $id
     * @return mixed
     */
    private static function touchProduct($data, $id = null)
    {
        $data = self::decodePrice($data);
        /**
         * filter data for security
         */
        $basic_data = self::filterBasicData($data);
        $meta_data = self::filterMetaData($data);

        if (is_null($id)) { //如果id为null, 则为创建, 反之为更新
            $basic_data['product_no'] = uniqid('pn_');
            $basic_data['status'] = ProductProtocol::VAR_PRODUCT_STATUS_UP;
        }
        /**
         * 将attributes序列化储存
         */
        $meta_data['attributes'] = json_encode($meta_data['attributes']);
        /**
         * create an product object
         */
        $product_basic_obj = DB::transaction(function () use ($id, $basic_data, $meta_data) {
            $product_basic_obj = Product::updateOrCreate(['id' => $id], $basic_data);
            $id = $product_basic_obj->id;
            $meta_data['product_id'] = $id;

            ProductMeta::updateOrCreate(['product_id' => $id], $meta_data);

            return $product_basic_obj;
        });

        return $product_basic_obj;
    }

    /**
     * @param $id
     * @return int
     */
    public static function delete($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            /**
             * detach group
             */
            $product->groups()->detach();
            /**
             * detach images
             */
            $product->images()->detach();
            //解除标签绑定

            /**
             * retrive all skus
             */

            ProductSkuService::deleteByProduct($id);


            /**
             * delete all favs
             */
            FavService::deleteCauseProductDeleted($id);

            /**
             * delete all comments
             */
            CommentService::deleteByProduct($id);

            /**
             * destroy product
             */
            $product->delete();

            DB::commit();

            return 1;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

