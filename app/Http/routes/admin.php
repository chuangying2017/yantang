<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 18/11/2015
 * Time: 4:21 PM
 */

Route::group(['prefix' => 'admin'], function(){

    Route::get('login', [
        'as' => 'admin.login',
        'uses' => 'Auth\AdminAuthController@getLogin'
    ]);

    Route::get('logout', [
        'as' => 'admin.logout',
        'uses' => 'Auth\AdminAuthController@getLogout'
    ]);

    Route::post('login', [
        'as' => 'admin.login.store',
        'uses' => 'Auth\AdminAuthController@postLogin'
    ]);

    Route::group(['middleware' => 'auth.admin'], function(){

        get('/dashboard', [
            'as' => 'admin.dashboard',
            'uses' => 'AdminController@index'
        ]);

        resource('account', 'AdminAccountController');


    });

});
