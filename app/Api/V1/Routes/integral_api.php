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
                $api->resource('fetchIntegral','FetchIntegralController',['only'=> ['index','show','update']]);
});