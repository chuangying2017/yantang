<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:39 AM
 */

namespace App\Services\Product\Category;

use App\Models\Category;


/**
 * Class CategoryService
 * @package App\Services\Product\Category
 */
class CategoryService
{
    /**
     * @param $name
     * @param int $pid
     * @param string $category_cover
     * @param string $desc
     * @return Category
     * @throws \Exception
     */
    public static function create($name, $pid = 0, $category_cover = "", $desc = "")
    {
        return CategoryRepository::create($name, $pid, $category_cover, $desc);
    }

    /**
     * @param $id
     * @param $data
     */
    public static function update($id, $data)
    {
        return CategoryRepository::update($id, $data);
    }

    /**
     * @param $id
     * @return int
     */
    public static function delete($id)
    {
        return CategoryRepository::delete($id);
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
        $parents = Category::where('pid', 0)->get(['id', 'name']);
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
        return Category::where('pid', $pid)->get(['id', 'name']);
    }

}
