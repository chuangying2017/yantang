<?php namespace App\Repositories\Category;

interface CategoryRepositoryContract {

    public function create($name, $desc, $cover_image, $index, $pid = null);

    public function update($cat_id, $name, $desc, $cover_image, $index, $pid = null);

    public function delete($cat_id);

    public function getAll();

    public function get($cat_id);

}
