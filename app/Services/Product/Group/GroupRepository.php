<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:04 PM
 */

namespace App\Services\Product\Group;


use App\Models\Group;
use Pheanstalk\Exception;

class GroupRepository
{
    /**
     * create a new group
     * @param $data
     *  - (string) name
     *  - (string) group_cover
     *  - (string) desc
     * @return string|static
     */
    public static function create($data)
    {
        try {

            $data = array_only($data, ['name', 'group_cover', 'desc']);
            $group = Group::create($data);
            return $group;

        } catch (Exception $e) {

            return $e->getMessage();
        }
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

            $group = Group::find($id);
            if (!$group) {
                throw new Exception('GROUP NOT FOUND');
            }
            $data = array_only($data, ['name', 'group_cover', 'desc']);
            $group->udpate($data);
            return 1;

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

            $group = Group::find($id);
            if (!$group) {
                throw new Exception('GROUP NOT FOUND');
            }
            $group->delete();

            $group->products()->detach();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
