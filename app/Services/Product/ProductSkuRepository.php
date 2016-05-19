<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 1/12/2015
 * Time: 10:36 AM
 */

namespace App\Services\Product;

use App\Models\Product\AttributeValue;
use App\Models\Product\ProductSku;
use Exception;
use Log;


/**
 * Class ProductSkuRepository
 * @package App\Services\Product
 */
class ProductSkuRepository {

    /**
     * @param $data
     * @param $product_id
     * @return mixed
     */
    public static function create($data, $product_id)
    {
        $attribute_values = AttributeValue::with('attribute')->whereIn('id', $data['attribute_value_ids'])->get();
        $attributes = [];
        foreach ($attribute_values as $attribute_value) {
            $attributes[] = [
                'attribute_value_id'   => $attribute_value['id'],
                'attribute_value_name' => $attribute_value['value'],
                'attribute_id'         => $attribute_value['attribute']['id'],
                'attribute_name'       => $attribute_value['attribute']['name'],
            ];
        }

        $sku = ProductSku::create([
            'name'        => $data['name'],
            'product_id'  => $product_id,
            'sku_no'      => uniqid('psn_'),
            'stock'       => $data['stock'],
            'price'       => store_price($data['price']),
            'cover_image' => $data['cover_image'],
            'attributes'  => json_encode($attributes)
        ]);

        $sku->attributeValues()->attach($data['attribute_value_ids']);

        return $sku;
    }


    /**
     * @param $id
     * @param $data
     * @return string
     * @throws Exception
     */
    public static function update($id, $data)
    {
        $attribute_value_ids = $data['attribute_value_ids'];
        $sku = ProductSku::find($id);
        if ( ! $sku) {
            throw new Exception('SKU NOT FOUND');
        }

        $attribute_values = AttributeValue::with('attribute')->whereIn('id', $data['attribute_value_ids'])->get();
        $attributes = [];
        foreach ($attribute_values as $attribute_value) {
            $attributes[] = [
                'attribute_value_id'   => $attribute_value['id'],
                'attribute_value_name' => $attribute_value['value'],
                'attribute_id'         => $attribute_value['attribute']['id'],
                'attribute_name'       => $attribute_value['attribute']['name'],
            ];
        }

        $data = array_only($data, ['name', 'stock', 'price', 'sales', 'cover_image']);

        $data['attributes'] = json_encode($attributes);
        $data['price'] = store_price($data['price']);

        $sku->update($data);

        $sku->attributeValues()->sync($attribute_value_ids);

        return 1;

    }

    /**
     * @param $id
     * @return int|string
     * @throws Exception
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
