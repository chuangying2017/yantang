<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiFormatHelpers;
use App\Http\Traits\ApiHelpers;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class Controller extends BaseController {

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiHelpers, ApiFormatHelpers, Helpers;


    protected function getCurrentAuthUserId()
    {
        $user = $this->getCurrentAuthUser();

        return $user['id'];
    }

    protected function getCurrentAuthUser()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return $user;
    }

}
