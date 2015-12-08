<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:04 PM
 */

namespace App\Services\Product\Attribute;


use App\Models\Attribute;
use DB;
use Exception;

/**
 * Class AttributeRepository
 * @package App\Services\Product\Attribute
 */
class AttributeRepository {

    public static function lists($merchant_id = 0)
    {
        return Attribute::whereIn('merchant_id', self::transformMerchantId($merchant_id))->get(['id', 'name', 'merchant_id']);
    }

    public static function transformMerchantId($merchant_id = 0)
    {
        if ($merchant_id === 0) {
            return [0];
        }

        return [$merchant_id, 0];
    }

    /**
     * return by name or create a new attribute
     * @param $name
     * @return static
     */
    public static function create($name, $merchant_id = null)
    {
        $attr = Attribute::firstOrCreate([
            'name'        => $name,
            'merchant_id' => $merchant_id
        ]);

        return $attr;
    }

    /**
     * update an attribute
     * @param $id
     * @param $name
     * @return string
     */
    public static function update($id, $name)
    {
        try {

            $attr = Attribute::findOrFail($id);
            $attr->name = $name;
            $attr->save();

            return $attr;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * delete an attribute
     * @param $id
     * @return int
     */
    public static function delete($id)
    {
        try {
            DB::beginTransaction();

            $attr = Attribute::findOrFail($id);
            /**
             * delete attribute vaules
             */
            $attr->values()->delete();
            /**
             * detach categories
             */
            $attr->categories()->detach();
            /**
             * self delete
             */
            $attr->delete();

            DB::commit();

            return 1;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
