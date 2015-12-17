<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:39 AM
 */

namespace App\Services\Product\Group;

use App\Models\Group;


/**
 * Class GroupService
 * @package App\Services\Product\Group
 */
class GroupService {

    public static function lists()
    {
        try {
            $groups = GroupRepository::lists();
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
            return GroupRepository::create($data);
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
        return GroupRepository::update($id, $data);
    }

    /**
     * @param $id
     */
    public static function delete($id)
    {
        return GroupRepository::delete($id);
    }

    /**
     * get a group info by id
     * @param $id
     * @return mixed
     */
    public static function show($id)
    {
        return Group::findOrFail($id);
    }

    public static function bindProducts($group_id, $product_id)
    {
        $product_id = to_array($product_id);

        return GroupRepository::bindingProducts($group_id, $product_id);
    }
}
