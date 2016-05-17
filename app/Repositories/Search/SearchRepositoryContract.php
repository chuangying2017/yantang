<?php namespace App\Repositories\Search;

interface SearchRepositoryContract {

    public function init();

    public function get();

    public function create();

    public function update();

    public function delete();
}
