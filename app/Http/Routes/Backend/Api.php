<?php


resource('categories', 'AdminCategoryController');

post('brands/categories', [
    'as'   => 'brands.bind.category',
    'uses' => 'AdminBrandController@bindBrandsToCategory'
]);
resource('brands', 'AdminBrandController');


Route::group(['prefix' => 'marketing'], function () {
    resource('coupons', 'AdminMarketingCouponController');

});

