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

});
$api->resource('categories', 'CategoryController');


$api->resource('products', 'ProductController');
$api->resource('attributes', 'AttributeController');
$api->resource('attributes.values', 'AttributeValueController', ['only' => ['store', 'destroy']]);

$api->resource('brands', 'BrandController');


$api->put('groups/{group_id}/products', [
    'as'   => 'groups.bind.products',
    'uses' => 'GroupController@bindingProducts'
]);
$api->resource('groups', 'GroupController');

$api->put('sections/{section_id}/products', [
    'as'   => 'sections.bind.products',
    'uses' => 'SectionController@bindingProducts'
]);
$api->resource('sections', 'SectionController');


$api->group(['prefix' => 'images'], function ($api) {
    $api->get('/', [
        'as'   => 'images.index',
        'uses' => 'ImageController@index'
    ]);

    $api->delete('/', [
        'as'   => 'images.delete',
        'uses' => 'ImageController@delete'
    ]);

    $api->get('token', [
        'as'   => 'images.token',
        'uses' => 'ImageController@token'
    ]);

    $api->get('/callback', [
        'as'   => 'qiniu.callback',
        'uses' => 'ImageController@callback'
    ]);
});
