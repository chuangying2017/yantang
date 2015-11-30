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

class AttributeRepository
{
    /**
     * create a new attribute
     * @param $name
     */
    public static function create($name)
    {
        try {
            DB::beginTransaction();

            $attr = Attribute::firstOrCreate([
                'name' => $name
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * bind an attribute with categories
     * @param $id
     * @param $category_ids
     * @return mixed
     */
    public static function bindCategories($id, $category_ids)
    {
        $attr = Attribute::findOrFail($id);
        return $attr->categoeies()->sync($category_ids);
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

            DB::table('attribute_values')->where('attibute_id', $id)->update([
                'attribute_name' => $name
            ]);

            DB::commit();
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
            $attr->delete();

            $attr->categories()->detach();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * get an attribute by id
     * @param $name
     * @return mixed
     */
    public static function firstOrCreate($name)
    {
        return Attribute::firstOrCreate([
            'name' => $name
        ]);
    }

    /**
     * get all attributes by category
     * @param $category_id
     * @return mixed
     */
    public static function getAllByCategory($category_id)
    {
        $category = Category::findOrFail($category_id);

        return $category->attributes()->get();
    }
}
