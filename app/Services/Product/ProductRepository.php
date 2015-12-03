<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 3:58 PM
 */

namespace App\Services\Product;


use App\Models\Product;
use App\Models\ProductSku;
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
    protected static function filterData($data)
    {
        $rules = [
            'merchant_id', 'type', 'stocks', 'origin_id',
            'title', 'price', 'limit', 'express_fee',
            'member_discount', 'with_invoice', 'with_care', 'cover_image',
            'desc', 'detail', 'status', 'open_status', 'open_time', 'category_id'
        ];
        return array_only($data, $rules);
    }

    /**
     * product create process
     * @param $data
     * @return bool|string
     *
     * data structure
     * ==============
     * data =>
     *   basic_info
     *   image_ids
     *   group_ids
     *   attributes
     *   skus
     */
    public static function create($data)
    {
        try {
            DB::beginTransaction();

            $product = self::touchProduct($data);

            /**
             * create skus
             */
            for ($i = 0; $i < count($data['skus']); $i++) {
                ProductSkuService::createSku($data['skus'][$i], $product->id);
            }

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
            ProductSkuService::updateSkus($data, $product);
            /**
             * link group
             */
            $product->groups()->attach($data['group_ids']);
            /**
             * link image
             */
            $product->images()->attach($data['image_ids']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }


    /**
     * @param $data
     * @return mixed
     */
    private static function touchProduct($data)
    {
        /**
         * filter data for security
         */
        $basic_info = self::filterData($data['basic_info']);

        if (!isset($basic_info['product_id'])) {
            /**
             * unique product id
             */
            $basic_info['product_id'] = uniqid('pro_');
        }
        /**
         * create an product object
         */
        $product = Product::updateOrCreate(['id' => $basic_info['product_id']], $basic_info);

        return $product;
    }

    /**
     * @param $id
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
            /**
             * retrive all skus
             */
            $skus = DB::table('product_sku')->where('product_id', $id);
            foreach ($skus as $sku) {
                $temp = ProductSku::find($sku->id);
                /**
                 * detach attribute values
                 */
                $temp->attributeValues()->detach();
                /**
                 * destroy sku
                 */
                $temp->destroy();
            }
            /**
             * destroy product
             */
            $product->destory();

            DB::commit();
            $product->destroy();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}

