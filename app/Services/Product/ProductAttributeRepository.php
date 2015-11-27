<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:04 PM
 */

namespace App\Services\Product;


use App\Models\Attribute;
use App\Models\Category;

class AttributeRepository
{
    /**
     * create a new attribute
     * @param $name
     * @param $category_ids
     */
    public static function create($name, $category_ids)
    {
        try {
            DB::beginTransaction();

            $attr = Attribute::firstOrCreate([
                'name' => $name
            ]);

            $attr->categories()->attach($category_ids);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * update an attribute
     * @param $id
     * @param $name
     * @param $category_ids
     */
    public static function update($id, $name, $category_ids)
    {
        try {
            DB::beginTransaction();

            $attr = Attribute::findOrFail($id);
            $attr->name = $name;
            $attr->save();

            $attr->categories()->sync($category_ids);

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

            $attr->categories()->sync([]);
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

    public static function getAllByCategory($category_id)
    {
        $category = Category::findOrFail($category_id);

        return $category->attributes()->get();
    }
}
