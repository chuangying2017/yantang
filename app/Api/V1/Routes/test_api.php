<?php


/**
 * Tools
 */
$api->group(['namespace' => 'Tool', 'prefix' => 'test'], function ($api) {

    $api->group(['prefix' => 'address'], function ($api) {
        $api->post('check', 'AddressController@check')->name('test.address.check');
    });

});
