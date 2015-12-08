<?php

$router->group(['prefix' => 'categories'], function ($router) {


    put('brands/{brand_id}', [
        'as'   => 'brand.bind.categories',
        'uses' => 'AdminBrandController@bindBrandToCategories'
    ]);

    put('attributes/{attribute_id}', [
        'as'   => 'attribute.bind.categories',
        'uses' => 'AdminAttributeController@bindAttributeToCategories'
    ]);

    resource('/', 'AdminCategoryController');

});


resource('products', 'AdminProductController');
resource('attributes', 'AdminAttributeController');
resource('attributes.values', 'AdminAttributeValueController', ['only' => ['store', 'destroy']]);


resource('brands', 'AdminBrandController');


$router->group(['namespace' => 'Marketing','prefix' => 'marketing'], function () {
    resource('coupons', 'AdminCouponController');
});

