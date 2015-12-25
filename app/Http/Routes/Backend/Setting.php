<?php
$router->group([

    'prefix' => 'setting',
    'namespace' => 'Setting'

], function () use ($router) {


    $router->group([
        'prefix' => 'frontpage'
    ], function () use ($router) {
        get('/', 'FrontpageController@index');

        resource('navs', 'NavController');
        resource('banners', 'BannerController');
        resource('sections', 'SectionController');
    });
});
