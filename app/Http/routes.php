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


/*
 * Test routes
 */
require app_path('Http/routes/test.php');
require app_path('Http/routes/marketing.php');


//Route::group(['middleware' => ''], function () {

get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function () {

    resource('categories', 'Api\CategoryController', ['only' => ['index', 'show']]);


});
//});

Route::controller('wechat', 'Auth\WechatAuthController');



