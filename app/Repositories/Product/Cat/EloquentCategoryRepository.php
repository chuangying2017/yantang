<?php namespace App\Repositories\Product\Cat;

use App\Models\Product\CategoryAbstract;
use App\Repositories\Category\CategoryProtocol;
use App\Repositories\Category\CategoryRepositoryAbstract;

class EloquentCategoryRepository extends CategoryRepositoryAbstract {

    protected function init()
    {
        $this->setType(CategoryProtocol::TYPE_OF_MAIN)
            ->setModel(CategoryAbstract::class);
    }
}
