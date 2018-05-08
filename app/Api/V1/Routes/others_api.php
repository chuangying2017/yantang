<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/8/008
 * Time: 12:02
 */


    $api->group(['namespace'=>'Admin\Others','prefix'=>'others'],function($api){
        $api->get('protocol/find','Protocols@index');
        $api->post('protocol/edit','Protocols@protocoledit');
    });
