<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20/020
 * Time: 11:41
 */

$api->group(['namespace'=>'Integral','middleware'=>'api.auth','prefix'=>'integral'],function ($api){

                $api->resource('IntegralShow','ShowPageController');
});