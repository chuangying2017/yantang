<?php

$api->get('nav', [
    'as'   => 'home.nav',
    'uses' => 'IndexController@getNav'
]);

$api->get('banners', [
    'as'   => 'home.banners',
    'uses' => 'IndexController@getBanners'
]);

$api->get('sections', [
    'as'   => 'home.sections',
    'uses' => 'IndexController@getSections'
]);




