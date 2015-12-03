<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:03 PM
 */
namespace App\Services\Product\Category;


use App\Models\Category;
use DB;
use Exception;

class CategoryRepository
{

    /**
     * create a new category
     * @param $name
     * @param int $pid
     * @param string $category_cover
     * @param string $desc
     * @return Category
     * @throws Exception
     */
    public static function create($name, $pid = 0, $category_cover = "", $desc = "")
    {
        if ($pid !== 0) {
            $parent = Category::find($pid);
            if (!$parent) throw new Exception('parent not existed');
        }

        $category = new Category;
        $category->name = $name;
        $category->pid = $pid;
        $category->category_cover = $category_cover;
        $category->desc = $desc;
        $category->save();
        return $category;

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

        return $category;
    }

    /**
     * delete a category
     * @param $id
     * @return int
     */
    public static function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return 1;
    }

}
