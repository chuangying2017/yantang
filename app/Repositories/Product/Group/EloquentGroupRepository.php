<?php namespace App\Services\Product\Group;

use App\Models\Product\Group;
use App\Repositories\Category\CategoryProtocol;
use App\Repositories\Category\CategoryRepositoryAbstract;

class GroupRepositoryAbstract extends CategoryRepositoryAbstract {

    protected function init()
    {
        $this->setType(CategoryProtocol::TYPE_OF_GROUP)
            ->setModel(Group::class);
    }


}
