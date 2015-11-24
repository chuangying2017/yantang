<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:04 PM
 */

namespace App\Services\Product;


use App\Models\Attribute;

class AttributeRepository
{
    public static function create($data)
    {
        $attr = new Attribute;

        $attr->name = $data['name'];
        $attr->type = $data['type'];
        $attr->save();
    }

    public static function update($id, $data)
    {
        $attr = Attribute::findOrFail($id);
        $attr->name = $data['name'];
        $attr->type = $data['type'];
        $attr->save();
    }

    public static function delete($id)
    {
        $attr = Attribute::findOrFail($id);
        $attr->delete();
    }

    public static function getById($id)
    {
        return Attribute::findOrFail($id);
    }

    public static function getAll()
    {
        return Attribute::all();
    }
}
