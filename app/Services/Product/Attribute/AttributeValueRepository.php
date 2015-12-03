<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:04 PM
 */

namespace App\Services\Product\Attribute;


use App\Models\Attribute;
use App\Models\AttributeValues;

class AttributeValueRepository {

    /**
     * create a new attribute
     * @param $attribute_id
     * @param $value
     * @param $merchant_id
     */
    public static function firstOrCreate($attribute_id, $value, $merchant_id)
    {
//        try {
//            DB::beginTransaction();

        $attribute = Attribute::findOrFail($attribute_id);

        $value = AttributeValues::firstOrCreate([
            'attribute_id'   => $attribute_id,
            'value'          => $value,
            'client_id'      => $merchant_id,
            'attribute_name' => $attribute->name
        ]);

        return $value;

//
//            DB::commit();
//        } catch (Exception $e) {
//            DB::rollBack();
//        }
    }
}
