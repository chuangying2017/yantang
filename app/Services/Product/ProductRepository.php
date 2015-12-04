<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 3:58 PM
 */

namespace App\Services\Product;


use App\Models\Product;
use App\Models\ProductMeta;
use App\Models\ProductSku;
use App\Services\Product\Comment\CommentServce;
use App\Services\Product\Comment\CommentService;
use App\Services\Product\Fav\FavService;
use DB;
use Exception;

/**
 * Class ProductRepository
 * @package App\Services\Product
 */
class ProductRepository
{
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
            'cover_image', 'open_status', 'open_time'
        ];
        return array_only($data, $rules);
    }

    private static function filterMetaData($data)
    {
        $rules = [
            'detail', 'is_virtual', 'origin_id', 'express_fee',
            'with_invoice', 'with_care'
        ];
        return array_only($data, $rules);
    }

    /**
     * product create process
     * @param $data
     * @return bool|string
     * @structure
     *  - (array) basic_info
     *      - (string) product_no: 商品编码, 后端生成
     *      - *(integer) brand_id
     *      - *(integer) category_id
     *      - *(integer) mechant_id
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
     *      - *(text) detail
     *      - (bool) is_virtual = 0
     *      - (string) origin_id
     *      - (integer) express_fee = 0
     *      - (bool) with_invoice
     *      - (bool) with_care
     *  - *(array) skus
     *  - (array) image_ids
     *  - (array) group_ids
     */
    public static function create($data)
    {
        try {
            DB::beginTransaction();

            $product = self::touchProduct($data['basic_info']);

            /**
             * create skus
             */
            ProductSkuService::create($data['skus'], $product->id);

            /**
             * link group
             */
            $product->groups()->attach($data['group_ids']);
            /**
             * link image
             */
            $product->images()->attach($data['image_ids']);

            DB::commit();

            return true;

        } catch (Exception $e) {
            DB::rollBack();
            return "error: " . $e->getMessage();
        }
    }

    /**
     * update a product
     * @param $id
     * @param $data
     */
    public static function update($id, $data)
    {

        try {
            DB::beginTransaction();
            /**
             * filter data for security
             */
            $product = self::touchProduct($data, $id);
            /**
             * update skus
             */
            ProductSkuService::sync($data['skus'], $product);
            /**
             * link group
             */
            $product->groups()->sync($data['group_ids']);
            /**
             * link image
             */
            $product->images()->sync($data['image_ids']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }


    /**
     * @param $data
     * @param null $id
     * @return mixed
     */
    private static function touchProduct($data, $id = null)
    {
        /**
         * filter data for security
         */
        $basic_data = self::filterBasicData($data);
        $meta_data = self::filterMetaData($data);

        if (is_null($id)) { //如果id为null, 则为创建, 反之为更新
            $basic_data['product_no'] = uniqid('pn_');
            $basic_data['status'] = ProductConst::VAR_PRODUCT_STATUS_UP;
        }
        /**
         * 将attributes序列化储存
         */
        $meta_data['attributes'] = json_encode($meta_data['attributes']);
        /**
         * create an product object
         */
        $product_basic_obj = Product::updateOrCreate(['id' => $id], $basic_data);
        ProductMeta::updateOrCreate(['product_id' => $id], $meta_data);

        return $product_basic_obj;
    }

    /**
     * @param $id
     */
    public static function delete($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::find($id);
            if (!$product) {
                throw new Exception('PRODUCT NOT FOUND');
            }
            /**
             * detach group
             */
            $product->groups()->detach();
            /**
             * detach images
             */
            $product->images()->detach();
            /**
             * retrive all skus
             */
            ProductSkuService::deleteByProduct($id);

            /**
             * delete all favs
             */
            FavService::deleteByProduct($id);

            /**
             * delete all comments
             */
            CommentService::deleteByProduct($id);

            /**
             * destroy product
             */
            $product->destory();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}

