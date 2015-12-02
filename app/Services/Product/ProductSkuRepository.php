<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 1/12/2015
 * Time: 10:36 AM
 */

namespace App\Services\Product;


class ProductSkuRepository
{
    /**
     * @param $data
     * @param $product_id
     * @return mixed
     * @internal param $sku
     * @internal param $product
     */
    public static function create($data, $product_id)
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
     * @param $data
     * @param $product
     */
    public static function update($data, $product)
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
