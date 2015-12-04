<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:04 PM
 */

namespace App\Services\Product\Attribute;


use App\Models\Attribute;
use App\Models\AttributeValue;
use Pheanstalk\Exception;

class AttributeValueRepository
{

    /**
     * create a new attribute
     * @param $attribute_id
     * @param $value
     * @return string|static
     */
    public static function create($attribute_id, $value)
    {
        try {

            if (!Attribute::find($attribute_id)) {
                throw new Exception('ATTRIBUTE NOT FOUND');
            }

            $value = AttributeValue::firstOrCreate([
                'attribute_id' => $attribute_id,
                'value' => $value
            ]);

            return $value;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 删除一个attribute value 只为attribute服务
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        try {
            DB::beginTransaction();
            $value = AttributeValue::findOrFail($id);
            /**
             * detach skus
             */
            $value->skus()->detach();
            $value->delete();
            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
