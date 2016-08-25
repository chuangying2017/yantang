<?php namespace App\Repositories\Client\UserGroup;
use App\Models\Client\UserGroup;

class UserGroupRepository extends UserGroupRepositoryAbstract{

    protected function setModel()
    {
        $this->model = UserGroup::class;
    }
}
