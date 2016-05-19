<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:39 AM
 */

namespace App\Services\Product\Category;

use App\Models\Product\Category;


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
    public static function create($name, $category_cover = "", $desc = "", $pid = null)
    {
        return CategoryRepository::create($name, $category_cover, $desc, $pid);
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
        $category = CategoryRepository::find($id);
        if ($category->isLeaf()) {
            return CategoryRepository::delete($id);
        }

        throw new \Exception('分类拥有子分类,不可删除');
    }

    public static function restore($id)
    {

    }


    /**
     * get all categories
     * @return mixed
     */
    public static function getAll()
    {
        return Category::all();
    }

    public static function show($category_id)
    {
        return Category::findOrFail($category_id);
    }

    public static function findIdByName($category_name)
    {
        return Category::where('name', $category_name)->pluck('id');
    }

    public static function getLeavesId($category_id, $string = false)
    {
        try {
            $category = CategoryRepository::find($category_id);

            if ($category->isLeaf()) {
                return [$category_id];
            }

            $categories = $category->getLeaves(['id'])->toArray();

            $data = [];
            foreach ($categories as $category) {
                $data[] = $category['id'];
            }

            return count($data) ? ($string ? implode(',', $data) : $data) : null;
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * get category tree
     * @param null $category_id
     * @return mixed
     */
    public static function getTree($category_id = null, $decode = true)
    {
        $mark = true;

        //todo@bryant cant work
        if (is_null($category_id)) {
            $parents = Category::roots()->get();

            if ( ! count($parents)) {
                return [];
            }

            $mark = false;

            foreach ($parents as $key => $parent) {
                $parents[ $key ] = self::getSingleTree($parent, $mark, $decode);
            }
        } else {
            $parents = self::getSingleTree($category_id, $mark, $decode);
        }

        return $parents;
    }

    /**
     * @param $category_id
     * @param bool|true $mark
     * @return mixed
     */
    protected static function getSingleTree($category_id, $mark = true, $decode = true)
    {
        $node = $category_id instanceof Category ? $category_id : Category::findOrFail($category_id);

        $parent = $node->getRoot();
        $parent = $parent->getDescendantsAndSelf(['id', 'pid', 'name', 'category_cover', 'created_at']);

        if ($mark) {
            $parent = self::markActive($parent, $node);
        }
        if ($decode) {
            return $parent->toHierarchy()->first();
        }

        return $parent;
    }

    /**
     * @param $parent
     * @param $node
     * @return mixed
     */
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
