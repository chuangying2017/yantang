<?php namespace App\Repositories\Category;

interface CategoryRepositoryContract {

    public function create();

    public function update();

    public function delete();

    public function getAll();

    public function get();

}
