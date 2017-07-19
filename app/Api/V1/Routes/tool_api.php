<?php


/**
 * Tools
 */
$api->group(['namespace' => 'Tool', 'prefix' => 'tool'], function ($api) {
    $api->post('giftcard', 'GiftcardController@send')
        ->name('tool.giftcard.dispatch')
        ->middleware('valid.server');
});
