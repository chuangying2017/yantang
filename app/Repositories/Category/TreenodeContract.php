<?php namespace App\Repositories\Category;
interface TreeNodeContract {

    public function makeChild($parent_node, $child_node);

    public function buildTree($tree_data);

    public function getFullTree();

    public function getSingleTree();

    public function getAllRoots();

    public function getAllRootsId();

    public function getSingleTreeLeaves();

    public function getSingleTreeLeavesId();

}
