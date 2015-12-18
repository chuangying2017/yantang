<?php namespace App\Services\Product\Section;

use App\Models\Section;


/**
 * Class SectionService
 * @package App\Services\Product\Section
 */
class SectionService {

    public static function lists()
    {
        try {
            $groups = SectionRepository::lists();
        } catch (\Exception $e) {
            throw $e;
        }

        return $groups;
    }

    /**
     * @param $data
     * @return string|static
     */
    public static function create($data)
    {
        try {
            return SectionRepository::create($data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $data
     * @return string
     */
    public static function update($id, $data)
    {
        return SectionRepository::update($id, $data);
    }

    /**
     * @param $id
     */
    public static function delete($id)
    {
        return SectionRepository::delete($id);
    }

    /**
     * get a group info by id
     * @param $id
     * @return mixed
     */
    public static function show($id)
    {
        return Section::findOrFail($id);
    }

    public static function bindProducts($group_id, $product_id)
    {
        $product_id = to_array($product_id);

        return SectionRepository::bindingProducts($group_id, $product_id);
    }
}
