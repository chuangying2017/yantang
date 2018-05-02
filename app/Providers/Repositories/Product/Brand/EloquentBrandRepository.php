<?php namespace App\Repositories\Product\Brand;

use App\Models\Product\Brand;
use App\Repositories\Category\CategoryProtocol;
use App\Repositories\Category\CategoryRepositoryAbstract;

class EloquentBrandRepository extends CategoryRepositoryAbstract implements BrandRepositoryContract{

    protected function init()
    {
        $this->setType(CategoryProtocol::TYPE_OF_BRAND)
            ->setModel(Brand::class);
    }
}
