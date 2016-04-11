<?php

$api->post('qiniu/callback', [
    'as'   => 'qiniu.callback',
    'uses' => 'QiniuController@store'
]);

