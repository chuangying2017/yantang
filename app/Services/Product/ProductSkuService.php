<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 25/11/2015
 * Time: 11:44 AM
 */

namespace App\Services\Product;

use App\Models\ProductSku;
use App\Models\ProductSkuView;

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
        return ProductSkuRepository::create($data, $product_id);
    }

    /**
     * @param $id
     * @param $data
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
    public static function getInfo($product_sku_ids)
    {
        $ids = [];
        if (is_array($product_sku_ids)) {
            $ids = $product_sku_ids;
        } else {
            $ids[] = $product_sku_ids;
        }

        $data = [];
        foreach ($ids as $id) {
            $sku = ProductSkuView::findOrFail($id);
            $data[] = $sku;
        }

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

        return $sku->increment('stock', $quantity);
    }

    /**
     * @param $product_sku_id
     * @param $quantity
     * @return mixed
     */
    public static function stockDown($product_sku_id, $quantity)
    {
        $sku = ProductSku::findOrFail($product_sku_id);

        return $sku->decrement('stock', $quantity);
    }

    /**
     * @param array $queryArr
     * @return array
     */
    public static function afford($queryArr = array())
    {
        $data = [];
        foreach ($queryArr as $query) {
            $sku = ProductSkuView::findOrFail($query->product_sku_id);
            $temp = [
                "product_sku_id" => $query->product_sku_id,
                'data' => $sku
            ];
            if ($sku->stock >= $query->quantity) {
                $temp['code'] = ProductConst::CODE_SKU_AFFORD_OK;
            } else {
                $temp['code'] = ProductConst::CODE_SKU_NOT_AFFORD;
                $temp['err_msg'] = ProductConst::MSG_SKU_NOT_AFFORD;
            }
            $data[] = $temp;
        }

        return $data;
    }
}
