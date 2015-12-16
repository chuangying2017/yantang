<?php

$api->group(['prefix' => 'categories'], function ($api) {

    $api->put('brands/{brand_id}', [
        'as'   => 'brand.bind.categories',
        'uses' => 'BrandController@bindBrandToCategories'
    ]);

    $api->put('attributes/{attribute_id}', [
        'as'   => 'attribute.bind.categories',
        'uses' => 'AttributeController@bindAttributeToCategories'
    ]);

    $api->resource('/', 'CategoryController');
});


$api->resource('products', 'ProductController');
$api->resource('attributes', 'AttributeController');
$api->resource('attributes.values', 'AttributeValueController', ['only' => ['store', 'destroy']]);


$api->resource('brands', 'BrandController');


$api->group(['namespace' => 'Marketing', 'prefix' => 'marketing'], function ($api) {
    $api->resource('coupons', 'CouponController');
});

