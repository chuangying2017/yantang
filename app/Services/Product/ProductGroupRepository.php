<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:04 PM
 */

namespace App\Services\Product;


use App\Models\Group;

class ProductGroupRepository
{
    /**
     * create a new group
     * @param $data
     */
    public static function create($data)
    {
        $group = new Group;

        $group->name = $data['name'];
        $group->group_cover = $data['group_cover'];
        $group->desc = $data['desc'];
        $group->save();
    }

    /**
     * update an existed group
     * @param $id
     * @param $data
     * @return string
     */
    public static function update($id, $data)
    {
        try {

            $group = Group::findOrFail($id);
            return $group->udpate($data);

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * delete an existed group
     * @param $id
     */
    public static function delete($id)
    {
        try {
            DB::beginTransaction();

            $group = Group::findOrFail($id);
            $group->delete();

            $group->products()->detach();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
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
