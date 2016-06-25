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
        $redirects = [
            'client' => 'http://client.yt.weazm.com/auth/wechatAuth',
            'station' => 'http://station.yt.weazm.com/auth/wechatAuth',
            'store' => 'http://store.yt.weazm.com/auth/wechatAuth',
            'staff' => 'http://staff.yt.weazm.com/auth/wechatAuth',
            'admin' => 'http://admin.yt.weazm.com/auth/wechatAuth',
        ];

        $role = $request->input('role');

        if (!isset($redirects[$role])) {
            throw new \Exception('role' . $role . ' 不存在', 401);
        }

        return redirect()->to($redirects[$role]);
    }


}
