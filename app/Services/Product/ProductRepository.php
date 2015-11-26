<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 3:58 PM
 */

namespace App\Services\Product;


use App\Models\Product;
use DB;

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
             * loop the attributes and build the relationship among product attribute and attribute_value
             */
            foreach ($data['attributes'] as $attribute) {
                foreach ($attribute->values as $value) {
                    self::attachProductAttrValue($product, $attribute, $value);
                }
            }
            /**
             * create skus
             */
            for ($i = 0; $i < count($data['skus']); $i++) {
                self::createSku($data['skus'][$i], $product->id);
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
             * update product attribute value
             */
            self::updateProductAttrValue($data, $product);
            /**
             * update skus
             */
            self::updateSkus($data, $product);
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
     * @param $product_id
     * @return mixed
     * @internal param $sku
     * @internal param $product
     */
    private static function createSku($data, $product_id)
    {
        $sku = ProductSku::create([
            'name' => $data['name'],
            'product_id' => $product_id,
            'sku_no' => uniqid('psn_'),
            'stock' => $data['stock'],
            'price' => $data['price'],
            'cover_image' => $data['cover_image'],
        ]);
        /**
         * link attributes
         */
        $sku->attributeValues()->attach($data['attributes_value_ids']);
        return $sku;
    }

    /**
     * @param $product_id
     * @param $attribute_id
     * @param $value_id
     */
    private static function attachProductAttrValue($product_id, $attribute_id, $value_id)
    {
        return DB::table('product_attribute_value')->insert([
            "product_id" => $product_id,
            "attribute_id" => $attribute_id,
            "attribute_value_id" => $value_id,
            "symbol" => $product_id . '/' . $attribute_id . '/' . $value_id
        ]);
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
     * @param $data
     * @param $product
     */
    private static function updateProductAttrValue($data, $product)
    {
        $productAttrVauleSymbols = DB::table('product_attribute_value')->where('product_id', $product->id)->lists('symbol');
        $newSymbols = [];
        /**
         * get the array of new symbols
         */
        foreach ($data['attributes'] as $attribute) {
            foreach ($attribute->values as $value) {
                $newSymbols[] = $product->id . '/' . $attribute->id . '/' . $value->id;
            }
        }

        $detachSymbols = array_diff($productAttrVauleSymbols, $newSymbols);
        $attachSymbols = array_diff($newSymbols, $productAttrVauleSymbols);

        /**
         * remove the record which is not in the new array
         */
        foreach ($detachSymbols as $symbol) {
            DB::table('product_attribute_value')->where('symbol', $symbol)->delete();
        }

        /**
         * add the record the db which is new
         */
        foreach ($attachSymbols as $symbol) {
            $temp = explode('/', $symbol);
            self::attachProductAttrValue($temp[0], $temp[1], $temp[2]);
        }
    }

    /**
     * @param $data
     * @param $product
     */
    private static function updateSkus($data, $product)
    {
        /**
         * 保留的 sku id
         */
        $remainSkuIds = [];
        /**
         * 数据库中的sku id
         */
        $oldSkuIds = $product->skus()->lists('id');
        /**
         * 遍历数据中的sku, 如果有id的说明是已存的, 放进remainSkuIds, 如果没有的说明是新的, 加到数据库中
         */
        foreach ($data['skus'] as $sku) {
            if (isset($sku['id'])) {
                $remainSkuIds[] = $sku[id];
            } else {
                $sku = self::createSku($sku, $product);
            }
        }
        /**
         * 对比保存的id数据和原来的数组, 找出删除的Ids
         */
        $detachSkuIds = array_diff($remainSkuIds, $oldSkuIds);
        /**
         * 删除不用的sku
         */
        foreach ($detachSkuIds as $skuId) {
            ProductSku::destroy($skuId);
        }
    }
}

