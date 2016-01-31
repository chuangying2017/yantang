<?php

$router->group([
    'namespace' => 'Product'
], function () use ($router) {
    resource('products', 'ProductController');
    get('products/{id}/operate/{action}', 'ProductController@operate');
    resource('groups', 'GroupController');
    resource('brands', 'BrandController');
    resource('channels', 'ChannelController');
    resource('categories', 'CategoryController');
    resource('attributes', 'AttributeController');
});
