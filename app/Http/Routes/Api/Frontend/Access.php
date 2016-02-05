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
        $api->get('auth/password/change', 'PasswordController@getChangePassword');
        $api->post('auth/password/change', 'PasswordController@postChangePassword')->name('password.change');
    });

    /**
     * These reoutes require the user NOT be logged in
     */
    $api->get('auth/login/{provider}', 'AuthController@loginThirdPartyUrl')->name('auth.provider.url');
    $api->get('auth/login/{provider}', 'AuthController@loginThirdParty')->name('auth.provider');
    $api->get('account/confirm/{token}', 'AuthController@confirmAccount')->name('account.confirm');
    $api->get('account/confirm/resend/{user_id}', 'AuthController@resendConfirmationEmail')->name('account.confirm.resend');

    $api->controller('auth', 'AuthController');
    $api->controller('password', 'PasswordController');
});
