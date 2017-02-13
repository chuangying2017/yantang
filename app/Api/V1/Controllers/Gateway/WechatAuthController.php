<?php

namespace App\Api\V1\Controllers\Gateway;

use App\Api\V1\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;

class WechatAuthController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect(Request $request)
    {
        $redirects = config('services.weixin.redirect_urls');

        $role = $request->input('role');

        if ($role == 'card') {
            $state = $request->input('state');
            $response = \Socialite::driver('weixin')->getAccessTokenResponse($request->input('code'));
            $openid = $response['openid'];

            return redirect()->to("http://hyf.hi-card.cn/hicardqrcode/qrCode/showWxPay?param1=$state&openId=$openid");
        }

        if (!isset($redirects[$role])) {
            throw new \Exception('role' . $role . ' 不存在', 401);
        }

        return redirect()->to($redirects[$role] . '?code=' . $request->input('code'));
    }


}
