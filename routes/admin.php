<?php

// Auth::routes();
Route::group([], function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout')->name('logout');

    // Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    // Route::post('password/reset', 'ResetPasswordController@reset');
    // Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    // Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
});

Route::group(['middleware' => 'auth:admin', 'prefix' => 'users'], function () {
    Route::get('/', 'AdminController@users')->name('admin.users');

    Route::get('/chgpass/{id}', 'AdminController@showChangePassword')->name('admin.chgpassword');
    Route::post('/chgpass/{id}', 'AdminController@storeChangePassword');

    Route::get('/register', 'RegisterController@showRegistrationForm')->name('admin.register');
    Route::post('/register', 'RegisterController@register');
});


Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/', 'AdminController@index')->name('admin.index');

    Route::get('/probecube', 'DeviceController@probecube')->name('admin.probecube');
    Route::get('/independent', 'DeviceController@independent')->name('admin.independent');


    Route::resource('thingspeak', 'ThingspeakController', ['names' => [
        'store' => 'admin.thingspeak.store',
        'index' => 'admin.thingspeak.index',
        'create' => 'admin.thingspeak.create',
        'destroy' => 'admin.thingspeak.destroy',
        'update' => 'admin.thingspeak.update',
        'show' => 'admin.thingspeak.show',
        'edit' => 'admin.thingspeak.edit',
    ]]);
});
