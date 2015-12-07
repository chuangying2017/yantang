<?php


resource('categories', 'AdminCategoryController');
resource('brands', 'AdminBrandController');


Route::group(['prefix' => 'marketing'], function () {
    resource('coupons', 'AdminMarketingCouponController');

});

