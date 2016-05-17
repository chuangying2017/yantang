<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 25/11/2015
 * Time: 11:44 AM
 */

namespace App\Services\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductSku;
use App\Models\ProductSkuView;
use Exception;
use Log;

/**
 * Class ProductSkuService
 * @package App\Services\Product
 */
class ProductSkuService
{

    /**
     * @param $data
     * @param $product_id
     * @return mixed
     */
    public static function create($data, $product_id)
    {
        $items = (is_array($data) && !isset($data['name'])) ? $data : [$data];
        $skus = [];
        foreach ($items as $item) {
            $skus[] = ProductSkuRepository::create($item, $product_id);
        }

        return $skus;
    }

    /**
     * @param $id
     * @param $data
     * @return string
     */
    public static function update($id, $data)
    {
        return ProductSkuRepository::update($id, $data);
    }

    /**
     * get sku info
     * @param array|integer $product_sku_ids
     * @return array
     */
    public static function show($product_sku_ids)
    {
        $ids = is_array($product_sku_ids) ? $product_sku_ids : [$product_sku_ids];

        $data = ProductSkuView::whereIn('id', $ids)->get;

        return $data;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getByProduct($id)
    {
        $product = Product::findOrFail($id);

        return $product->skuViews()->get();
    }

    /**
     * @param $product_sku_id
     * @param $quantity
     * @return mixed
     */
    public static function stockUp($product_sku_id, $quantity)
    {
        $sku = ProductSku::findOrFail($product_sku_id);

        $sku->increment('stock', $quantity);
        $sku->decrement('sales', $quantity);

        return $quantity;
    }

    /**
     * @param $product_sku_id
     * @param $quantity
     * @return mixed
     */
    public static function stockDown($product_sku_id, $quantity)
    {
        $sku = ProductSku::findOrFail($product_sku_id);

        $sku->decrement('stock', $quantity);
        $sku->increment('sales', $quantity);

        return $quantity;
    }

    /**
     * @param array $queryArr
     * @return array
     */
    public static function afford($queryArr = array())
    {
        $data = [];
        foreach ($queryArr as $query) {
            $sku = ProductSkuView::findOrFail($query['product_sku_id']);
            $temp = [
                'product_sku_id' => $query['product_sku_id'],
                'data' => $sku
            ];
            #TODO @bryant 判断其他边界条件,例如商品是否上架,是否开售,是否限购,etc
            if ($sku->stock >= $query['quantity']) {
                $temp['code'] = ProductConst::CODE_SKU_AFFORD_OK;
            } else {
                $temp['code'] = ProductConst::CODE_SKU_NOT_AFFORD;
                $temp['err_msg'] = ProductConst::MSG_SKU_NOT_AFFORD;
            }
            $data[] = $temp;
        }

        return $data;
    }

    /**
     * @param $product_id
     * @return int|string
     */
    public static function deleteByProduct($product_id)
    {
        $product = Product::find($product_id);
        if (!$product) {
            throw new Exception('PRODUCT NOT FOUND');
        }
        $skus = $product->skus()->get();
        foreach ($skus as $sku) {
            ProductSkuRepository::delete($sku->id);
        }

        return 1;
    }

    /**
     * 更新一个商品的skus, 将不要的删除, 新的增加
     * @param $skus
     * @param $product
     * @return int|string
     */
    public static function sync($skus, $product)
    {
        try {
            /**
             * 保留的 sku id
             */
            $remainSkuIds = [];
            $remainSkus = [];
            /**
             * 数据库中的sku id
             */
            $oldSkuIds = $product->skus()->lists('id')->all();

            /**
             * 遍历数据中的sku, 如果有id的说明是已存的, 放进remainSkuIds, 如果没有的说明是新的, 加到数据库中
             */
            foreach ($skus as $sku) {
                if (isset($sku['id'])) {
                    $remainSkuIds[] = $sku['id'];
                    $remainSkus[] = $sku;
                } else {
                    self::create($sku, $product['id']);
                }
            }
            $oldSkuIds = array_map('intval', $oldSkuIds);


            /**
             * 对比保存的id数据和原来的数组, 找出删除的Ids
             */
            $detachSkuIds = array_diff($oldSkuIds, $remainSkuIds);
            /**
             * 删除不用的sku
             */
            foreach ($detachSkuIds as $skuId) {
                self::delete($skuId);
            }
            foreach ($remainSkus as $remainSku) {
                ProductSkuRepository::update($remainSku['id'], $remainSku);
            }

            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $id
     * @return mixed
     */
    public static function delete($id)
    {
        return ProductSkuRepository::delete($id);
    }
}
