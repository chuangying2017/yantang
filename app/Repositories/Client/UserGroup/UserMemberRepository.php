<?php namespace App\Repositories\Client\UserGroup;
use App\Models\Client\Member;

class UserMemberRepository extends UserGroupRepositoryAbstract{

    protected function setModel()
    {
        $this->model = Member::class;
    }
}
