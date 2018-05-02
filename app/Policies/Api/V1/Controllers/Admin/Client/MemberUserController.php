<?php namespace App\Api\V1\Controllers\Admin\Client;
use App\Repositories\Client\UserGroup\UserMemberRepository;

class MemberUserController extends GroupUserControllerAbstract{

    public function __construct(UserMemberRepository $groupRepo)
    {
        parent::__construct($groupRepo);
    }

}
