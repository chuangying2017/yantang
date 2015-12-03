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

class AttributeValueRepository
{
    /**
     * create a new attribute
     * @param $attribute_id
     * @param $value
     * @param $merchant_id
     */
    public static function firstOrCreate($attribute_id, $value, $merchant_id, $cover_image = null)
    {
        try {
            DB::beginTransaction();

            $attribute = Attribute::findOrFail($attribute_id);

            $value = AttributeValues::firstOrCreate([
                'attribute_id' => $attribute_id,
                'value' => $value,
                'client_id' => $merchant_id,
                'attribute_name' => $attribute->name,
                'cover_image' => $cover_image
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * 更新一个属性值 （只为brand服务）
     * @param $id
     * @param $data
     * @return bool
     */
    public static function update($id, $data)
    {
        try {
            DB::beginTransaction();

            $data = array_only($data, ['value', 'cover_image']);

            AttributeValues::find($id)->udpate($data);

            DB::commit();

            return true;

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * 删除一个attribute value 只为brand服务
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        try {
            DB::beginTransaction();
            $value = AttributeValues::findOrFail($id);
            $value->skus()->detach();
            $value->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
