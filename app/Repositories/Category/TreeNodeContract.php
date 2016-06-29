<?php namespace App\Repositories\Category;
interface TreeNodeContract {

    public function makeChild($parent_node, $child_node);

    public function buildTree($tree_data);

    public function getFullTree();

    public function getSingleTree($node_id);

    public function getAllRoots();

    public function getAllRootsId();

    public function getSingleTreeLeaves($node_id);

    public function getSingleTreeLeavesId($node_id);

}
