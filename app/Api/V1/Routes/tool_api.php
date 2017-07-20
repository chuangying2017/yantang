<?php


/**
 * Tools
 */
$api->group(['namespace' => 'Tool', 'prefix' => 'tool'], function ($api) {
    $api->group(['middleware'=>'valid.server'], function($api){
        $api->resource('giftcard', 'GiftcardController',['only'=>['index','store']]);
    });
});
