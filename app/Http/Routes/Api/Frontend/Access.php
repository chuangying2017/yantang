<?php

/**
 * Frontend Access Controllers
 */
$api->group(['namespace' => 'Auth'], function () use ($api) {
    /**
     * These routes require the user to be logged in
     */
    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->get('auth/logout', 'AuthController@getLogout');
//        $api->get('auth/password/change', 'PasswordController@getChangePassword');
        $api->post('auth/password/change', 'PasswordController@postChangePassword')->name('password.change');
    });


    /**
     * These reoutes require the user NOT be logged in
     */

    $api->get('auth/base/weixin', [
        'as'   => 'weixin.base.url',
        'uses' => 'AuthController@weixinUrl'
    ]);

    $api->post('auth/base/weixin', [
        'as'   => 'weixin.base.info',
        'uses' => 'AuthController@weixinOpenid'
    ]);

    $api->get('auth/login/{provider}', 'AuthController@loginThirdPartyUrl')->name('auth.provider.url');
    $api->post('auth/login/{provider}', 'AuthController@loginThirdParty')->name('auth.provider');
//    $api->get('account/confirm/{token}', 'AuthController@confirmAccount')->name('account.confirm');
    $api->post('auth/reset', 'PasswordController@postReset')->name('auth.reset');
    $api->controller('auth', 'AuthController');
    $api->controller('password', 'PasswordController');


});
