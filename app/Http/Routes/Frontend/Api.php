<?php


resource('categories', 'CategoryController', ['only' => ['index', 'show']]);


$router->group(['prefix' => 'marketing'], function () {
    resource('coupons', 'Marketing\CouponController');
});
