<?php namespace App\Repositories\Search;

interface SearchRepositoryContract {

    public function init();

    public function get($keyword = null);

    public function create($model);

    public function update($model);

    public function delete($model);

    public function hot($limit);

}
