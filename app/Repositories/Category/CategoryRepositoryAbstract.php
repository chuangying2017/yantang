<?php namespace App\Repositories\Category;

use App\Models\Product\CategoryAbstract;

abstract class CategoryRepositoryAbstract implements CategoryRepositoryContract, TreeNodeContract {

    protected $type;

    protected $model;

    protected abstract function init();

    public function __construct()
    {
        $this->init();
    }

    public function getByIdProducts($product_id)
    {
        return \DB::table('product_category')->where('product_id', $product_id)->pluck('cat_id');
    }

    public function create($name, $desc, $cover_image, $priority = 0, $pid = null)
    {
        $model = $this->getModel();
        $cat = $model::create(compact('name', 'desc', 'cover_image', 'priority', 'pid'));

        if ($pid) {
            $this->makeChild($this->get($pid), $cat);
        }

        return $cat;
    }

    public function update($cat_id, $name, $desc, $cover_image, $priority, $pid = null)
    {
        $cat = $this->get($cat_id);
        $cat->fill(compact('name', 'desc', 'cover_image', 'priority', 'pid'))->save();

        if ($pid) {
            $this->makeChild($this->get($pid), $cat);
        }

        return $cat;
    }

    public function delete($cat_id)
    {
        $model = $this->getModel();
        return $model::whereIn('id', to_array($cat_id))->delete();
    }

    public function getAll()
    {
        $model = $this->getModel();
        return $model::orderBy('priority', 'asc')->get();
    }

	/**
     * @param $cat_id
     * @return CategoryAbstract
     */
    public function get($cat_id)
    {
        $model = $this->getModel();
        if ($cat_id instanceof $model) {
            return $cat_id;
        }
        return $model::find($cat_id);
    }

    public function makeChild($parent_node, $child_node)
    {
        $child_node->makeChildOf($parent_node);
    }

    public function buildTree($tree_data)
    {
        $model = $this->getModel();
        return $model->buildTree($tree_data);
    }

    public function getFullTree()
    {
        $roots = $this->getAllRoots();
        if (!count($roots)) {
            return $roots;
        }
        foreach ($roots as $key => $root) {
            $roots->$key = $this->getSingleTree($root);
        }
        return $roots;
    }

    public function getSingleTree($node_id, $decode = true)
    {
        $node = $this->get($node_id);
        $tree = $node->getRoot()->getDescendantsAndSelf();

        if ($decode) {
            return $tree->toHierarchy()->first();
        }

        return $tree;
    }

    public function getAllRoots()
    {
        $model = $this->getModel();
        return $model::roots()->get();
    }

    public function getAllRootsId()
    {
        $model = $this->getModel();
        return $model::whereIsNull('pid')->lists('id')->all();
    }

    public function getSingleTreeLeaves($node_id)
    {
        $node = $this->get($node_id);

        if ($node->isLeaf()) {
            return $node;
        }

        return $node->getLeaves();
    }

    public function getSingleTreeLeavesId($node_id)
    {
        $leaves = $this->getSingleTreeLeaves($node_id);

        if (count($leaves) == 1) {
            return [$leaves['id']];
        }

        $leaves_id = [];
        foreach ($leaves as $category) {
            $leaves_id[] = $category['id'];
        }

        return $leaves_id;
    }

    /**
     * @param mixed $type
     * @return CategoryRepositoryAbstract
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $model
     * @return CategoryRepositoryAbstract
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }
}
