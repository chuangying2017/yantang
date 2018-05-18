<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/8/008
 * Time: 12:02
 */


    $api->group(['namespace'=>'Admin\Others','prefix'=>'others'],function($api){
        $api->group(['middleware'=>'api.auth'],function ($api){
            $api->get('protocol/find','Protocols@index');
            $api->post('protocol/edit','Protocols@protocoledit');
            $api->post('protocol/setting/{setting_id}','Protocols@setting')->where('setting_id','\d+');
            $api->get('protocol/show/{id}', 'Protocols@show');
        });

        $api->get('reception','Protocols@index');//前台获取协议
        $api->get('testFileUnits','Protocols@testfile');//测试
    });
