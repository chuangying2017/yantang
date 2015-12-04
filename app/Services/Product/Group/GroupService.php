<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:39 AM
 */

namespace App\Services\Product\Group;


/**
 * Class GroupService
 * @package App\Services\Product\Group
 */
class GroupService
{
    /**
     * @param $data
     * @return string|static
     */
    public static function create($data)
    {
        return GroupRepository::create($data);
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
}
