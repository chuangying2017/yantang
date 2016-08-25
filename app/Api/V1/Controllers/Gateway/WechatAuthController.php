<?php

namespace App\Api\V1\Controllers\Gateway;

use App\API\V1\Controllers\Controller;
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

        if (!isset($redirects[$role])) {
            throw new \Exception('role' . $role . ' 不存在', 401);
        }

        return redirect()->to($redirects[$role] . '?code=' . $request->input('code'));
    }


}
