<?php

$api->group(['prefix' => 'categories'], function ($api) {

    $api->put('brands/{brand_id}', [
        'as'   => 'brand.bind.categories',
        'uses' => 'AdminBrandController@bindBrandToCategories'
    ]);

    $api->put('attributes/{attribute_id}', [
        'as'   => 'attribute.bind.categories',
        'uses' => 'AdminAttributeController@bindAttributeToCategories'
    ]);

    $api->resource('/', 'AdminCategoryController');
});


$api->resource('products', 'AdminProductController');
$api->resource('attributes', 'AdminAttributeController');
$api->resource('attributes.values', 'AdminAttributeValueController', ['only' => ['store', 'destroy']]);


$api->resource('brands', 'AdminBrandController');


$api->group(['namespace' => 'Marketing', 'prefix' => 'marketing'], function ($api) {
    $api->resource('coupons', 'AdminCouponController');
});

