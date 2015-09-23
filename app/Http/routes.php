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



if(App::environment() == 'local' || env('APP_DEBUG')) {
	
	Route::get('test', function(){

	});

	Route::get('test/token', function(){
		return Session::token();
	});

	Route::get('/test/login/{id}', function($id){
		Auth::user()->logout();
		Auth::user()->loginUsingId($id);
		return $id . 'login';
	});

	Route::get('/test/logout', function(){
		Auth::user()->logout();
	});

}

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


Route::group(['middleware' => 'auth.wechat'], function(){

	get('/', function(){
		return view('welcome');
	});

	Route::group(['prefix' => 'api'], function(){

		

	});


});


Route::controller('wechat', 'Auth\WechatAuthController');


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
