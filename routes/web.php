<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('/{latlng?}', 'HomeController@map')->where('latlng', '@[0-9.]+,[0-9.]+')->name('map');
Route::get('/list', 'HomeController@list')->name('list');
Route::get('/site', 'HomeController@site')->name('site');
Route::get('/datasource', 'HomeController@datasource')->name('datasource');

/* JSON */
Route::group(['prefix' => 'json'], function () {
    Route::get('airmap.json', 'JsonController@airmap');
    Route::get('{json}', 'JsonController@group')
        ->where('json', '.*\.json$')->name('json');

    Route::get('query-lastest', 'JsonController@lastest');
    Route::get('query-history', 'JsonController@history');

    Route::get('query-region', 'JsonController@region');
    Route::get('query-bounds', 'JsonController@bounds')->middleware('cors');
});

Route::get('fetchlog/{group}', 'FetchLogController@show')->name('fetchlog');

Route::group(['prefix' => 'widget'], function () {
    Route::get('create/{group}${uuid}', 'WidgetController@create')->name('widget.create');
    Route::get('{type}/{group}${uuid}', 'WidgetController@show')->name('widget.show');
});


/* Admin */
// Auth::routes();
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::group(['namespace' => 'Auth'], function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout')->name('logout');

    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'ResetPasswordController@reset');
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
});

Route::group(['prefix' => 'manager', 'middleware' => 'auth'], function () {
    Route::get('/', 'ManagerController@index')->name('manager.index');

    Route::get('/users', 'ManagerController@users')->name('manager.users');
    Route::get('/chgpass', 'ManagerController@showChangePassword')->name('manager.chgpassword');
    Route::post('/chgpass', 'ManagerController@storeChangePassword');

    Route::get('/probecube', 'ManagerController@probecube')->name('manager.probecube');
    Route::get('/independent', 'ManagerController@independent')->name('manager.independent');


    Route::resource('thingspeak', 'ThingspeakController');
    Route::get('thingspeak/{thingspeak}/fetch', 'ThingspeakController@fetch')->name('thingspeak.fetch');
    // Route::group(['prefix' => 'thingspeak'], function () {
    //     Route::get('create', 'ManagerController@createThingspeak')->name('manager.thingspeak.create');
    //     Route::post('store', 'ManagerController@storeThingspeak')->name('manager.thingspeak.store');
    //     Route::get('{id}', 'ManagerController@showThingspeak')->name('manager.thingspeak.show');
    //     Route::get('{id}/edit', 'ManagerController@editThingspeak')->name('manager.thingspeak.edit');
    //     Route::get('{id}/delete', 'ManagerController@deletedThingspeak')->name('manager.thingspeak.delete');
    // });
});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');