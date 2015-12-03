<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:04 PM
 */

namespace App\Services\Product\Attribute;


use App\Models\Attribute;
use App\Models\Category;
use App\Models\AttributeValue;
use DB;
use Exception;

class AttributeRepository
{
    /**
     * return by name or create a new attribute
     * @param $name
     */
    public static function create($name)
    {
//        try {
//            DB::beginTransaction();

            $attr = Attribute::firstOrCreate([
                'name' => $name
            ]);

//            DB::commit();

            return $attr;
//
//        } catch (Exception $e) {
//            DB::rollBack();
//        }
    }

    /**
     * update an attribute
     * @param $id
     * @param $name
     */
    public static function update($id, $name)
    {
        try {
            DB::beginTransaction();

            $attr = Attribute::findOrFail($id);
            $attr->name = $name;
            $attr->save();

            #todo @bryant 是否有误
            DB::table('attribute_values')->where('attibute_id', $id)->update([
                'attribute_name' => $name
            ]);

            DB::commit();
            return $attr;
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * delete an attribute
     * @param $id
     */
    public static function delete($id)
    {
        try {
            DB::beginTransaction();

            $attr = Attribute::findOrFail($id);
            $values = $attr->values()->get();
            foreach ($values as $value) {
                DB::table('sku_attribute_value')->where('attribute_value_id', $value->id)->delete();
                AttributeValue::destroy($value->id);
            }

            $attr->delete();
            $attr->categories()->detach();

            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

}
