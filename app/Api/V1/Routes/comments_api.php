<?php
/**
 * Created by PhpStorm.
 * User: 张伟
 * Date: 2018/6/4
 * Time: 23:31
 */
$api->group(['namespace'=>'Comments','prefix'=>'comments','middleware'=>['api.auth','access.routeNeedsRole:'.\App\Repositories\Backend\AccessProtocol::ROLE_OF_CLIENT]],function($api){
    $api->resource('clientComments','IndexController');
    $api->get('commentFetchIntegral','IndexController@fetchIntegral');
});