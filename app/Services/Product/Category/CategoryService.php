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
class CategoryService {

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
    public static function getTree($category_id = null)
    {
        if (is_null($category_id)) {
            $parents = Category::roots()->get();
            foreach ($parents as $key => $parent) {
                $parents[ $key ] = self::getSingleTree($parent, false);
            }
        } else {
            $parents = self::getSingleTree($category_id);
        }

        return $parents;
    }

    protected static function getSingleTree($category_id, $mark = true)
    {
        $node = $category_id instanceof Category ? $category_id : Category::find($category_id);

        $parent = $node->getRoot();
        $parent = $parent->getDescendantsAndSelf(['id', 'pid', 'name']);

        if ($mark) {
            $parent = self::markActive($parent, $node);
        }

        return $parent->toHierarchy()->first();
    }

    protected static function markActive($parent, $node)
    {
        $search_node = $node;
        while ( ! is_null($search_node->pid)) {
            foreach ($parent as $key => $value) {
                if ($value->id == $search_node->id) {
                    $parent[ $key ]['active'] = true;
                    foreach ($parent as $search_key => $search_value) {
                        if ($search_node->pid == $search_value->id) {
                            $search_node = $search_value;
                            break;
                        }
                    }
                    break;
                }
            }
        }
        $parent->active = true;

        return $parent;
    }

}
