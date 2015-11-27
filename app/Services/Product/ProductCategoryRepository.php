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
    /**
     * create a new category
     * @param $name
     * @param int $pid
     * @param string $category_cover
     * @param string $desc
     * @throws Exception
     */
    public static function create($name, $pid = 0, $category_cover = "", $desc = "")
    {
        if ($pid !== 0) {
            $parent = Category::find($pid);
            if (!$parent) throw new Exception('parent not existed');
        }
        try {
            DB::beginTransaction();

            $category = new Category;

            $category->name = $name;
            $category->pid = $pid;
            $category->category_cover = $category_cover;
            $category->desc = $desc;
            $category->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * update a category
     * @param $id
     * @param $data
     */
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

    public static function getChildren($pid)
    {
        return Category::where('pid', $pid)->get();
    }

}
