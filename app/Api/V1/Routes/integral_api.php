<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20/020
 * Time: 11:41
 */

$api->group(['namespace'=>'Integral','middleware'=>'api.auth'],function ($api){
            $api->group(['prefix'=>'integral','middleware'=>'access.routeNeedsRole'. \App\Repositories\Backend\AccessProtocol::ROLE_OF_CLIENT],function($api){
                $api->resource('IntegralShow','ShowPageController');
            });
});