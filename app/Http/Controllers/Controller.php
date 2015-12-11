<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiFormatHelpers;
use App\Http\Traits\ApiHelpers;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiHelpers, ApiFormatHelpers, Helpers;

    public function getCurrentAuthUserId()
    {
        #todo @troy get user from jwt
        $user_id = 1;
        return $user_id;
    }

}
