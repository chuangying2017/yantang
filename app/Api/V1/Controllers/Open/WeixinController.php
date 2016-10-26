<?php

namespace App\Api\V1\Controllers\Open;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WeixinController extends Controller {

    public function token()
    {
        $token = \EasyWeChat::access_token()->getToken();
        return $this->response->array(['access_token'  => $token, 'expires_in' => 2000]);
    }
}
