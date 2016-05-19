<?php namespace App\Repositories\Category;

use App\Models\Product\Category;

class EloquentCategoryRepository extends CategoryRepositoryAbstract {

    protected function init()
    {
        $this->setType(CategoryProtocol::TYPE_OF_MAIN)
            ->setModel(Category::class);
    }
}
