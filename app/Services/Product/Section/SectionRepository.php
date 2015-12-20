<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:04 PM
 */

namespace App\Services\Product\Section;


use App\Models\Section;
use App\Models\SectionProduct;
use App\Services\Product\ProductService;
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
            $sections = Section::with('products')->get();
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
            $section->products()->where('section_id', $id)->delete();
            $section->delete();

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function show($id)
    {
        return Section::with('products')->findOrFail($id);
    }


    public static function bindingProducts($section_id, $product_data)
    {
        try {
            $section = Section::findOrFail($section_id);


            $section->products()->delete();

            foreach ($product_data as $product) {
                $section_data = array_only($product, ['product_id', 'title', 'cover_image', 'price', 'index']);
                $section_data['section_id'] = $section_id;
                SectionProduct::create($section_data);
            }

            return self::show($section_id);
        } catch (Exception $e) {
            throw $e;
        }

        return 1;
    }

}
