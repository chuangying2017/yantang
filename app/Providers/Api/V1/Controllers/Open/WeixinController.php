<?php

namespace App\Api\V1\Controllers\Open;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use EasyWeChat;

class WeixinController extends Controller {

    public function token()
    {
        $token = \EasyWeChat::access_token()->getToken();
        return $this->response->array(['access_token'  => $token, 'expires_in' => 2000]);
    }

    public function jstoken(Request $request){
        $url = $request->input('url');
        $token = EasyWeChat::js()->setUrl($url)->config([], true);
        return $this->response->array( $token );
    }
}
