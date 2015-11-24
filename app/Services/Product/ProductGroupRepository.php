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
    public static function create($data)
    {
        $group = new Group;

        $group->title = $data['title'];
        $group->save();
    }

    public static function delete($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();
    }

    public static function getById($id)
    {
        return Group::findOrFail($id);
    }
}
