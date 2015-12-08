<?php


resource('categories', 'CategoryController', ['only' => ['index', 'show']]);

resource('products', 'ProductController', ['only' => 'index', 'show']);

resource('fav', 'FavController', ['only' => ['index', 'store', 'destroy']]);


$router->group(['prefix' => 'marketing'], function () {
    resource('coupons', 'Marketing\CouponController');
});
