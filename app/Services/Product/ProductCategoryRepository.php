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

    /**
     * delete a category
     * @param $id
     */
    public static function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }

    /**
     * get a category by id
     * @param $id
     * @return mixed
     */
    public static function getById($id)
    {
        return Category::findOrFail($id);
    }

    /**
     * get all categories
     * @return mixed
     */
    public static function getAll()
    {
        return Category::all();
    }

    /**
     * get category tree
     * @return mixed
     */
    public static function getTree()
    {
        $parents = Category::where('pid', 0)->get();
        foreach ($parents as $parent) {
            $parent->children = self::getChildren($parent->id);
        }
        return $parents;
    }

    /**
     * get children categories by parent id
     * @param $pid
     * @return mixed
     */
    public static function getChildren($pid)
    {
        return Category::where('pid', $pid)->get();
    }

}
