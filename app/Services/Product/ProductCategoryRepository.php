<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:03 PM
 */
namespace App\Services\Product;


use App\Models\Category;
use Exception;

class CategoryRepository
{
    public static function create($data)
    {
        $parent = Category::find($data['pid']);
        if (!$parent) throw new Exception('parent not existed');
        $category = new Category;

        $category->name = $data['name'];
        $category->pid = $data['pid'];
        $category->save();
    }

    public static function update($id, $data)
    {
        $category = Category::findOrFail($id);
        $category->name = $data['name'];
        $category->pid = $data['pid'];
        $category->save();
    }

    public static function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }

    public static function getById($id)
    {
        return Category::findOrFail($id);
    }

    public static function getAll()
    {
        return Category::all();
    }

    public static function getByPid($pid)
    {
        return Category::where('pid', $pid)->get();
    }
}
