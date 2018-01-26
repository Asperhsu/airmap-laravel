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
Route::get('/recruit', 'HomeController@recruit')->name('recruit');
Route::get('/about', 'HomeController@about')->name('about');
Route::get('/datasource', 'HomeController@datasource')->name('datasource');
Route::get('/dialy-gif', 'HomeController@dialyGif')->name('dialy-gif');

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


Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
