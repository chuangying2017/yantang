<?php namespace App\Services\Product\Group;

use App\Repositories\Category\EloquentCategoryRepository;

class EloquentGroupRepository extends EloquentCategoryRepository {

    protected function init()
    {
        $this->setType('brand');
    }

}
