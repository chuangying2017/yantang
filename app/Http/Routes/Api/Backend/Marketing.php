<?php

$api->group(['namespace' => 'Marketing', 'prefix' => 'marketing'], function ($api) {
    $api->resource('coupons', 'CouponController');
});
