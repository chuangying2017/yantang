<?php namespace App\Repositories\Category;

abstract class EloquentCategoryRepository implements CategoryRepositoryContract, TreeNodeContract {

    protected $type;

    protected abstract function init();

    public function __construct()
    {
        $this->init();
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function get()
    {
        // TODO: Implement get() method.
    }

    public function makeChild($parent_node, $child_node)
    {
        // TODO: Implement makeChild() method.
    }

    public function buildTree($tree_data)
    {
        // TODO: Implement buildTree() method.
    }

    public function getFullTree()
    {
        // TODO: Implement getFullTree() method.
    }

    public function getSingleTree()
    {
        // TODO: Implement getSingleTree() method.
    }

    public function getAllRoots()
    {
        // TODO: Implement getAllRoots() method.
    }

    public function getAllRootsId()
    {
        // TODO: Implement getAllRootsId() method.
    }

    public function getSingleTreeLeaves()
    {
        // TODO: Implement getSingleTreeLeaves() method.
    }

    public function getSingleTreeLeavesId()
    {
        // TODO: Implement getSingleTreeLeavesId() method.
    }

    /**
     * @param mixed $type
     * @return EloquentCategoryRepository
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
}
