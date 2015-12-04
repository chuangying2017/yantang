<?php


resource('categories', 'AdminCategoryController');

Route::group(['prefix' => 'marketing'], function () {
    resource('coupons', 'AdminMarketingCouponController');
});

