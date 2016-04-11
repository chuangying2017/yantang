<?php namespace App\Services\Product\Section;



/**
 * Class SectionService
 * @package App\Services\Product\Section
 */
class SectionService {

    public static function lists()
    {
        try {
            $sections = SectionRepository::lists();
        } catch (\Exception $e) {
            throw $e;
        }

        return $sections;
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
        return SectionRepository::show($id);
    }

    public static function bindProducts($section_id, $product_data)
    {
        return SectionRepository::bindingProducts($section_id, $product_data);
    }


}
