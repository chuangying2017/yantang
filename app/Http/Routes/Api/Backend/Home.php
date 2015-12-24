<?php

$api->resource('nav', 'NavController');
$api->resource('banners', 'BannerController');

$api->put('sections/{section_id}/products', [
    'as'   => 'sections.bind.products',
    'uses' => 'SectionController@bindingProducts'
]);
$api->resource('sections', 'SectionController');


