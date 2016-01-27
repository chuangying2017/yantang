<?php
/*
 * Test routes
 */

use App\Services\Product\ProductConst;

if (App::environment() == 'local' || env('APP_DEBUG')) {

    Route::get('test', function () {

        $redirect = \Socialite::with('weixin')->scopes(['snsapi_userinfo'])->redirect();

        return $redirect;

        return $redirect->setTargetUrl('http://auth.weazm.com/wechat/public/wechat/redirect?red_url=' . base64_encode($redirect->getTargetUrl()));
    });

    Route::get('test/token', function () {
        return csrf_token();
    });

    Route::get('/test/login/{id}', function ($id) {
        Auth::user()->logout();
        Auth::user()->loginUsingId($id);

        return $id . ' login ' . (Auth::user()->check() ? ' success' : ' fail');
    });

    Route::get('/test/logout', function () {
        Auth::user()->logout();
    });

}
