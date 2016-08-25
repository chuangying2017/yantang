<?php namespace App\Repositories\Product\Cat;

use App\Models\Product\Category;
use App\Repositories\Category\CategoryProtocol;
use App\Repositories\Category\CategoryRepositoryAbstract;

class EloquentCategoryRepository extends CategoryRepositoryAbstract {

    protected function init()
    {
        $this->setType(CategoryProtocol::TYPE_OF_MAIN)
            ->setModel(Category::class);
    }
}
