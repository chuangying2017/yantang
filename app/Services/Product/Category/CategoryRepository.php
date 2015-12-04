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
        try {
            $node = new Category;
            $node->name = $name;
            $node->pid = $pid;
            $node->category_cover = $category_cover;
            $node->desc = $desc;
            $node->save();

            if ($pid) {
                $parent = Category::find($pid);
                $node->makeChildOf($parent);
                $node->save();
            }
            return $node;
        } catch (Exception $e) {
            return $e->getMessage();
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
        Category::onlyTrashed()->where('id', $id)->restore();
        return 1;
    }

}
