<?php

namespace App\API\V1\Controllers;

use App\Services\Orders\Supports\PingxxProtocol;
use Auth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController {

    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests, Helpers;


    protected function getCurrentAuthUserId()
    {
        if ($user = $this->getCurrentAuthUser()) {
            return $user['id'];
        }

        return false;
    }


    protected function getCurrentAuthUser()
    {
        if (!JWTAuth::getToken()) {

            if (Auth::check()) {
                return Auth::user();
            }

            return false;
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();

            return $user;
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $this->response->errorUnauthorized('Token Expired');
        }

    }

}
