<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:39 AM
 */

namespace App\Services\Product\Group;


class GroupService
{
    public static function create($data)
    {
        return GroupRepository::create($data);
    }

    public static function update($id, $data)
    {
        return GroupRepository::update($id, $data);
    }

    public static function delete($id)
    {
        return GroupRepository::delete($id);
    }

    /**
     * get a group info by id
     * @param $id
     * @return mixed
     */
    public static function getById($id)
    {
        return Group::findOrFail($id);
    }
}
