<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20/020
 * Time: 11:41
 */

$api->group(['namespace'=>'Integral','middleware'=>'api.auth','prefix'=>'integral'],function ($api){

                $api->resource('IntegralShow','ShowPageController');
                $api->resource('IntegralUserAddress','UserAddress');
                $api->get('showMemberOrder','ShowPageController@meeting_record');
                $api->resource('fetchIntegral','FetchIntegralController',['only'=> ['index','show','update','store']]);
                $api->get('integralCard','IntegralCouponController@get_exchange');
                $api->get('integralCard/{id}','IntegralCouponController@get_show');
                $api->put('integralFetchCoupon/{convertId}','IntegralCouponController@put_integral');
                $api->get('integralRecord','IntegralCouponController@pull_integralRecord');
                $api->get('integralProtocol','IntegralCouponController@pull_protocol');
                $api->group(['middleware' => [\App\Api\V1\Middleware\IntegralSignMiddleware::class]],function($api)
                {
                    $api->get('integralSignGet','SignController@SignGet');
                    $api->resource('integralSignMonthAll','SignController',['only' => ['index']]);
                });
});