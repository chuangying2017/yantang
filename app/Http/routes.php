<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

require app_path('Http/Routes/test.php');
require app_path('Http/Routes/admin.php');




Route::group(['middleware' => 'auth.wechat'], function(){

	get('/', function(){
		return view('welcome');
	});

	Route::group(['prefix' => 'api'], function(){

	});
});

Route::controller('wechat', 'Auth\WechatAuthController');



