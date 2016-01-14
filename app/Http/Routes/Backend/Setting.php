<?php
$router->group([

    'prefix' => 'setting',
    'namespace' => 'Setting'

], function () use ($router) {


    get('/basic', 'SettingController@index');
    resource('navs', 'NavController');
    resource('banners', 'BannerController');
    resource('sections', 'SectionController');
});
