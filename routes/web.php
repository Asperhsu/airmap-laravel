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

Route::get('/{latlng?}', 'HomeController@index')->where('latlng', '@[0-9.]+,[0-9.]+')->name('home');
Route::get('/list', 'HomeController@list')->name('list');
Route::get('/datasource', 'HomeController@datasource')->name('datasource');
Route::get('/recruit', 'HomeController@recruit')->name('recruit');

Route::group(['prefix' => 'screenshot'], function () {
    Route::get('hourly', 'HomeController@screenshotHourly')->name('screenshot.hourly');
    Route::get('gif', 'HomeController@screenshotGif')->name('screenshot.gif');
});

Route::group(['prefix' => 'widget'], function () {
    Route::get('create/{group}${uuid}', 'WidgetController@create')->name('widget.create');
    Route::get('{type}/{group}${uuid}', 'WidgetController@show')->name('widget.show');
    Route::get('/', 'WidgetController@index')->name('widget.index');
});


/* JSON */
Route::group(['prefix' => 'json', 'middleware' => 'cors'], function () {
    Route::get('airmap.json', 'JsonController@airmap');
    Route::get('townmap.json', 'JsonController@townmap');
    Route::get('{json}', 'JsonController@group')
        ->where('json', '.*\.json$')->name('json');

    Route::get('query-lastest', 'JsonController@lastest');
    Route::get('query-history', 'JsonController@history');
    Route::get('query-prediction', 'JsonController@prediction');

    Route::get('query-region', 'JsonController@region');
    Route::get('query-bounds', 'JsonController@bounds');
});

// Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

/* V5 */
Route::post('/v5/accept-warning', 'V5Controller@acceptWarning');
Route::get('/v5/{any?}', 'V5Controller@index')->where('any', '.*');

/* v4 */
Route::get('/map{latlng?}', 'V4Controller@map')->where('latlng', '@[0-9.]+,[0-9.]+')->name('v4.map');
// Route::get('/list', 'V4Controller@list')->name('v4.list');
Route::get('/site', 'V4Controller@site')->name('v4.site');
