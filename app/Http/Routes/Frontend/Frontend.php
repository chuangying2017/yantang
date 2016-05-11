<?php

/**
 * Frontend Controllers
 */
Route::get('/', 'FrontendController@index')->name('home');
Route::get('macros', 'FrontendController@macros');

/**
 * These frontend controllers require the user to be logged in
 */
$router->group(['middleware' => 'auth'], function () {
    Route::get('dashboard', 'DashboardController@index')->name('frontend.dashboard');
    Route::get('profile/edit', 'ProfileController@edit')->name('frontend.profile.edit');
    Route::patch('profile/update', 'ProfileController@update')->name('frontend.profile.update');
});





