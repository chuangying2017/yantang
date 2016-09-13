<?php namespace App\Repositories\Category;

interface CategoryRepositoryContract {

    public function create($name, $desc, $cover_image, $priority, $pid = null);

    public function update($cat_id, $name, $desc, $cover_image, $priority, $pid = null);

    public function delete($cat_id);

    public function getAll();

    public function get($cat_id);

    public function getIdsByProducts($product_id);

    

}
