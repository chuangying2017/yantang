<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:04 PM
 */

namespace App\Services\Product\Group;


use App\Models\Group;
use DB;
use Pheanstalk\Exception;

class GroupRepository {

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
            throw $e;
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

            Group::where('id', $id)->update(array_only($data, ['name', 'group_cover', 'desc']));

            return Group::findOrFail($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function lists()
    {
        try {
            $groups = Group::get();
        } catch (\Exception $e) {
            throw $e;
        }

        return $groups;
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
            if ( ! $group) {
                throw new Exception('GROUP NOT FOUND');
            }
            $group->delete();

            $group->products()->detach();

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public static function bindingProducts($group_id, $product_id)
    {
        try {
            $group = Group::findOrFail($group_id);

            $group->products()->sync($product_id);
        } catch (Exception $e) {
            throw $e;
        }

        return 1;
    }
}
