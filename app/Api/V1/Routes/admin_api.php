<?php


/**
 *
 * Admin
 *
 */
$api->group(['namespace' => 'Admin', 'prefix' => 'admin'], function ($api) {

    $api->group(['middleware' => 'auth'], function ($api) {
        $api->group(['namespace' => 'Product'], function ($api) {
            $api->resource('products/mix', 'ProductMixController', ['only' => ['index']]);
            $api->resource('products', 'ProductController');
            $api->resource('attributes', 'AttributeController');
            $api->resource('attributes.values', 'AttributeValueController');
            $api->resource('brands', 'BrandController');
            $api->resource('groups', 'BrandController');
            $api->resource('cats', 'CategoryController');
        });

        $api->group(['namespace' => 'Campaign'], function ($api) {
            $api->resource('stores', 'StoreController');
            $api->resource('campaigns', 'CampaignController');
        });
    });
});
