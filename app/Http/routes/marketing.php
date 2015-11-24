<?

Route::group(['prefix' => 'api'], function () {

    Route::group(['prefix' => 'marketing'], function () {
        resource('coupons', 'Api\Marketing\CouponController');
    });


});


Route::group(['prefix' => 'admin/api'], function () {

    Route::group(['prefix' => 'marketing'], function () {
        resource('coupons', 'Admin\AdminMarketingCouponController');

    });

});
