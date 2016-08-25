<?php

namespace App\Api\V1\Controllers\Admin\Client;

use App\Repositories\Client\UserGroup\UserGroupRepository;

use App\Http\Requests;

class GroupController extends GroupControllerAbstract {

    /**
     * GroupController constructor.
     * @param UserGroupRepository $groupRepo
     */
    public function __construct(UserGroupRepository $groupRepo)
    {
        parent::__construct($groupRepo);
    }

}
