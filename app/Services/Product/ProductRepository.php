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
            'client_id', 'type', 'stocks', 'origin_id',
            'title', 'price', 'limit', 'express_fee',
            'member_discount', 'with_invoice', 'with_care',
            'desc', 'detail', 'status', 'open_status', 'open_time', 'category_id'
        ];
        return array_only($data, $rules);
    }

    /**
     * product create process
     * @param $data
     * @return bool|string
     */
    public static function create($data)
    {
//        $data = [
//            "category_id" => "",
//            "group_ids" => [],
//            "basic_info" => [
//
//            ],
//            "image_ids" => [],
//            "attributes" => [
//                [
//                    "id" => 1,
//                    "name" => "",
//                    "values" => [
//                        [
//                            "id" => "hhh",
//                            "name" => ""
//                        ],
//                        [
//                            "id" => "hhh",
//                            "name" => ""
//                        ]
//                    ]
//                ]
//            ],
//            "skus" => [
//                [
//                    "stock" => "",
//                    "price" => "",
//                    "name" => "",
//                    "attributes_value_ids" => []
//                ]
//            ],
//        ];

        /**
         * array: basic information
         */
        $basic_info = $data['basic_info'];
        /**
         * array: product images ids
         */
        $image_ids = $data['image_ids'];
        /**
         * array: product group ids
         */
        $group_ids = $data['group_ids'];
        /**
         * array: attributes
         */
        $attributes = $data['attributes'];
        /**
         * array: skus
         */
        $skus = $data['skus'];
        try {
            DB::beginTransaction();

            /**
             * filter data for security
             */
            $basic_info = self::filterData($basic_info);
            /**
             * unique product id
             */
            $basic_info['product_id'] = uniqid('pro_');
            /**
             * create an product object
             */
            $product = Product::create($basic_info);

            /**
             * loop the attributes and build the relationship among product attribute and attribute_value
             */
            foreach ($attributes as $attribute) {
                foreach ($attribute->values as $value) {
                    DB::table('product_attribute_value')->insert([
                        "product_id" => $product->id,
                        "attribute_id" => $attribute->id,
                        "attribute_value_id" => $value->id,
                        "symbol" => $product->id . '/' . $attribute->id . '/' . $value->id
                    ]);
                }
            }

            /**
             * create skus
             */
            for ($i = 0; $i < count($skus); $i++) {
                $sku = ProductSku::create([
                    'name' => $skus[$i]['name'],
                    'product_id' => $product->id,
                    'sku_no' => uniqid('psn_'),
                    'stock' => $skus[$i]['stock'],
                    'price' => $skus[$i]['price'],
                ]);
                /**
                 * link attributes
                 */
                $sku->attributeValues()->attach($skus[$i]['attributes_value_ids']);
            }

            /**
             * link group
             */
            $product->groups()->attach($group_ids);
            /**
             * link image
             */
            $product->images()->attach($image_ids);

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
        $product = Product::findOrFail($id);
        /**
         * array: basic information
         */
        $basic_info = $data['basic_info'];
        /**
         * array: product images ids
         */
        $image_ids = $data['image_ids'];
        /**
         * array: product group ids
         */
        $group_ids = $data['group_ids'];
        /**
         * array: attributes
         */
        $attributes = $data['attributes'];
        /**
         * array: skus
         */
        $skus = $data['skus'];
        try {
            DB::beginTransaction();
            /**
             * filter data for security
             */
            $basic_info = self::filterData($basic_info);

            $product = Product::updateOrCreate(['id' => $id], $basic_info);

            $productAttrVauleSymbols = DB::table('product_attribute_value')->where('product_id', $product->id)->lists('symbol');
            $newSymbols = [];
            $detachSymbols = [];
            $attachSymbols = [];

            /**
             * get the array of new symbols
             */
            foreach ($attributes as $attribute) {
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
                DB::table('product_attribute_value')->insert([
                    "product_id" => $temp[0],
                    "attribute_id" => $temp[1],
                    "attribute_value_id" => $temp[2],
                    "symbol" => $symbol
                ]);
            }

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
            foreach ($skus as $sku) {
                if (isset($sku['id'])) {
                    $remainSkuIds[] = $sku[id];
                } else {
                    $sku = ProductSku::create([
                        'name' => $sku['name'],
                        'product_id' => $product->id,
                        'sku_no' => uniqid('psn_'),
                        'stock' => $['stock'],
                        'price' => $['price'],
                    ]);
                    /**
                     * link attributes
                     */
                    $sku->attributeValues()->attach($sku['attributes_value_ids']);
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

            /**
             * link group
             */
            $product->groups()->attach($group_ids);
            /**
             * link image
             */
            $product->images()->attach($image_ids);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    public static function getById($id)
    {
        $product = Product::find($id);
        $skus = $product->skus()->get();

    }

    public static function getByCategory($category_id)
    {

    }
}

