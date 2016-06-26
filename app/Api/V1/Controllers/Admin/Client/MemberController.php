<?php

namespace App\Api\V1\Controllers\Admin\Client;

use App\Repositories\Client\UserGroup\UserMemberRepository;
use App\Http\Requests;

class MemberController extends GroupControllerAbstract {


    /**
     * MemberController constructor.
     * @param UserMemberRepository $groupRepo
     */
    public function __construct(UserMemberRepository $groupRepo)
    {
        parent::__construct($groupRepo);
    }

}
