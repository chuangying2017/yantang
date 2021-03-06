<?php

/**
 * 用户Auth,信息管理
 */
$api->group(['namespace' => 'Auth'], function () use ($api) {
    /**
     * These routes require the user to be logged in
     */
    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->get('auth/logout', 'AuthController@getLogout');
        $api->get('auth/password/change', 'PasswordController@getChangePassword');
        $api->post('auth/password/change', 'PasswordController@postChangePassword')->name('password.change');

        $api->get('users/info', ['uses' => 'UserController@getUserInfo', 'as' => 'user.info']);
        $api->put('users/info', ['uses' => 'UserController@updateUserInfo', 'as' => 'update.user.info']);

        $api->get('users/weixin/subscribe', ['uses' => 'UserController@weixinInfo', 'as' => 'user.weixin.info']);
    });

    /**
     * These routes require the user NOT be logged in
     */
    $api->group(['middleware' => 'guest', 'web'], function () use ($api) {
        $api->get('auth/login/{provider}', 'AuthController@loginThirdPartyUrl')->name('auth.provider.url');
        $api->post('auth/login/{provider}', 'AuthController@loginThirdParty')->name('auth.provider');

        $api->get('auth/logout', 'AuthController@getLogout');
        $api->post('auth/register', 'AuthController@postRegister');
        $api->post('auth/login', 'AuthController@postLogin');

//        $api->controller('password', 'PasswordController');
    });

    /**
     * Sms
     */
    $api->post('auth/sms/send-code', 'SmsController@postSendCode')->name('auth.sms.send');
    $api->get('auth/sms/debug', 'SmsController@getInfo')->name('auth.sms.debug');
});


$api->group(['namespace' => 'Client'], function () use ($api) {

    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->resource('users/address', 'AddressController');
        $api->resource('users/wallets/balance', 'WalletController@balance');
        $api->resource('users/wallets', 'WalletController', ['only' => ['index']]);
        $api->resource('users/recharge', 'ChargeController');
        $api->get('images/token', 'ImageController@token');
    });

});
