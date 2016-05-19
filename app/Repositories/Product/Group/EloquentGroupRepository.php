<?php namespace App\Repositories\Product\Group;

use App\Models\Product\Group;
use App\Repositories\Category\CategoryProtocol;
use App\Repositories\Category\CategoryRepositoryAbstract;

class EloquentGroupRepository extends CategoryRepositoryAbstract implements GroupRepositoryContract{

    protected function init()
    {
        $this->setType(CategoryProtocol::TYPE_OF_GROUP)
            ->setModel(Group::class);
    }


}
