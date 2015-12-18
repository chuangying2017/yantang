<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiFormatHelpers;
use App\Http\Traits\ApiHelpers;
use App\Services\Orders\Supports\PingxxProtocol;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiHelpers, ApiFormatHelpers, Helpers;


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
            return false;
        }

        $user = JWTAuth::parseToken()->authenticate();

        return $user;
    }

    protected function getAgent()
    {
        #todo 判断支付请求来源
        return \Request::input('agent', PingxxProtocol::AGENT_OF_PC);
    }

}
