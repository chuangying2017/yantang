<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Api\V1\Controllers', 'middleware' => 'cors'], function ($api) {

        /**
         * Frontend Access Controllers
         */
        $api->group(['namespace' => 'Auth'], function () use ($api) {
            /**
             * These routes require the user to be logged in
             */
            $api->group(['middleware' => 'auth'], function ($api) {
                $api->get('auth/logout', 'AuthController@getLogout');
                $api->get('auth/password/change', 'PasswordController@getChangePassword');
                $api->post('auth/password/change', 'PasswordController@postChangePassword')->name('password.change');
            });

            /**
             * These reoutes require the user NOT be logged in
             */
            $api->group(['middleware' => 'guest', 'web'], function () use ($api) {
                $api->get('auth/login/{provider}', 'AuthController@loginThirdPartyUrl')->name('auth.provider.url');
                $api->post('auth/login/{provider}', 'AuthController@loginThirdParty')->name('auth.provider');
                $api->controller('auth', 'AuthController');
                $api->controller('password', 'PasswordController');
            });

            /**
             * Sms
             */
            $api->post('auth/sms/send-code', 'SmsController@postSendCode')->name('auth.sms.send');
            $api->get('auth/sms/debug', 'SmsController@getInfo')->name('auth.sms.debug');
        });


        /**
         *
         * Admin
         *
         */
        $api->group(['namespace' => 'Admin', 'prefix' => 'admin'], function ($api) {
            $api->group(['namespace' => 'Product'], function ($api) {

                $api->resource('products', 'ProductController');

            });
        });

        /**
         *
         * Station
         *
         */
        $api->group(['namespace' => 'Subscribe\Station', 'prefix' => 'stations'], function ($api) {
            $api->get('products', 'ProductController@index');
        });
    });

});
