<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:03 PM
 */
namespace App\Services\Product\Category;


use App\Models\Product\Category;
use DB;
use Exception;

/**
 * Class CategoryRepository
 * @package App\Services\Product\Category
 */
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
    public static function create($name, $category_cover = "", $desc = "", $pid = null)
    {
        $pid = (is_numeric($pid) && $pid) ? $pid : null;
        $node = new Category();
        $node->name = $name;
        $node->pid = $pid;
        $node->category_cover = $category_cover;
        $node->desc = $desc;

        if (is_numeric($pid) && $pid) {
            $parent = Category::findOrFail($pid);
            $node->makeChildOf($parent);
        }
        $node->save();

        return $node;
    }

    /**
     * update a category
     * @param $id
     * @param $data
     */
    public static function update($id, $data)
    {
        $category = Category::findOrFail($id);
        $data = array_only($data, ['name', 'category_cover', 'desc']);
        $category->update($data);

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

    /**
     * @param $id
     * @return mixed
     */
    public static function restore($id)
    {
        $category = Category::onlyTrashed()->where('id', $id)->firstOrFail();

        if ($category->trashed()) {
            $category->restore();
            $pid = $category->pid;
            if ($pid) {
                $parent = Category::findOrFail($pid);
                $category->makeChildOf($parent);
            }
        }

        return 1;
    }

    /**
     * get a category by id
     * @param $id
     * @return mixed
     */
    public static function find($id)
    {
        return Category::findOrFail($id);
    }


}
