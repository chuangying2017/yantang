<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 1/12/2015
 * Time: 10:36 AM
 */

namespace App\Services\Product;

use App\Models\ProductSku;
use Exception;


/**
 * Class ProductSkuRepository
 * @package App\Services\Product
 */
class ProductSkuRepository {

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
            'name'        => $data['name'],
            'product_id'  => $product_id,
            'sku_no'      => uniqid('psn_'),
            'stock'       => $data['stock'],
            'price'       => $data['price'],
            'cover_image' => $data['cover_image'],
        ]);

        return $sku;
    }


    /**
     * @param $id
     * @param $data
     * @return string
     */
    public static function update($id, $data)
    {

        $sku = ProductSku::find($id);
        if ( ! $sku) {
            throw new Exception('SKU NOT FOUND');
        }
        $data = array_only($data, ['name', 'stock', 'price', 'sales', 'cover_image']);

        $sku->update($data);

        return 1;

    }

    /**
     * @param $id
     * @return int|string
     */
    public static function delete($id)
    {
        $sku = ProductSku::find($id);
        if ( ! $sku) {
            throw new Exception('PRODUCTSKU NOT FOUND');
        }
        /**
         * detach attribute values
         */
        $sku->attributeValues()->detach();
        /**
         * destroy sku
         */
        $sku->delete();

        return 1;

    }
}
