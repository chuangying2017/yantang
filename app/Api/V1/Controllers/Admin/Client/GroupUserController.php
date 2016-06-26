<?php namespace App\Api\V1\Controllers\Admin\Client;

use App\Repositories\Client\UserGroup\UserGroupRepository;

class GroupUserController extends GroupUserControllerAbstract {

    public function __construct(UserGroupRepository $groupRepo)
    {
        parent::__construct($groupRepo);
    }

}
