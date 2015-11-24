<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 3:58 PM
 */

namespace App\Services\Product;


use App\Models\Product;

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
            'client_id', 'type', 'stock', 'code',
            'title', 'price', 'limit', 'express_fee',
            'member_discount', 'with_invoice', 'with_care',
            'desc', 'detail', 'status', 'open_status', 'open_time'
        ];
        return array_only($data, $rules);
    }

    /**
     * product create process
     * @param $data
     */
    public static function create($data)
    {
        $data = [
            "basic_info" => [

            ],
            "image_ids" => [],
            "attributes" => [
                [
                    "id" => 1,
                    "name" => "",
                    "values" => [
                        [
                            "id" => "hhh",
                            "name" => ""
                        ],
                        [
                            "id" => "hhh",
                            "name" => ""
                        ]
                    ]
                ]
            ],
            "skus" => [
                [
                    "stock" => "",
                    "price" => "",
                    "name" => "",
                    "attributes_value_ids" => []
                ]
            ],
        ];
        try {
            DB::beginTransaction();

            $basic_info = self::filterData($data['basic_info']);
            $basic_info['product_id'] = uniqid('pro_');
            $product = Product::create($basic_info);

            $skus = $data['skus'];

            for ($i = 0; $i < count($skus); $i++) {
                $sku = ProductSku::create([
                    'name' => $skus[$i]['name'],
                    'product_id' => $product->id,
                    'product_sku_no' => uniqid('psn_'),
                    'stock' => $skus[$i]['stock'],
                    'price' => $skus[$i]['price'],
                ]);
                /**
                 * link attributes
                 */
                $sku->attribute()->attach($skus[$i]['attributes_ids']);
            }

            /**
             * link category
             */
            $product->category()->attach($data['category_id']);
            /**
             * link group
             */
            $product->group()->attach($data['group_ids']);
            /**
             * link image
             */
            $product->image()->attach($data['image_ids']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public static function update($id, $data)
    {
        try {
            DB::beginTransaction();

            $basic_info = self::filterData($data['basic_info']);
            $product = Product::findOrFail($id)->update($basic_info);

            $product->group()->sync($data['group_ids']);
            $product->image()->sync($data['images_ids']);

            $skus = $data['skus'];

            for ($i = 0; $i < count($skus); $i++) {
                if ($skus[$i]['product_sku_no'])
                    /**
                     * link attributes
                     */
                    $sku->attribute()->attach($skus[$i]['attributes_ids']);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    public static function getById($id)
    {
    }
}

