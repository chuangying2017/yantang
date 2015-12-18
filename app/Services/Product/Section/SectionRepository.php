<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:04 PM
 */

namespace App\Services\Product\Section;


use App\Models\Section;
use DB;
use Exception;

class SectionRepository {

    /**
     * create a new section
     * @param $data
     *  - (string) name
     *  - (string) section_cover
     *  - (string) desc
     * @return string|static
     */
    public static function create($data)
    {
        try {

            $data = array_only($data, ['title', 'style', 'url']);
            $section = Section::create($data);

            return $section;

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * update an existed section
     * @param $id
     * @param $data
     * @return string
     */
    public static function update($id, $data)
    {
        try {
            $section = Section::findOrFail($id);

            $section->fill(array_only($data, ['title', 'style', 'url']));
            $section->save();

            return $section;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function lists()
    {
        try {
            $sections = Section::get();
        } catch (\Exception $e) {
            throw $e;
        }


        return $sections;
    }

    /**
     * delete an existed section
     * @param $id
     */
    public static function delete($id)
    {
        try {
            DB::beginTransaction();

            $section = Section::findOrFail($id);
            $section->products()->detach();
            $section->delete();

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public static function bindingProducts($section_id, $product_id)
    {
        try {
            $section = Section::findOrFail($section_id);

            $section->products()->sync($product_id);
        } catch (Exception $e) {
            throw $e;
        }

        return 1;
    }
}
