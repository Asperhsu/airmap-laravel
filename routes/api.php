<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('icon/{value}', 'ApiController@makeIcon')->name('pm25icon');
Route::get('widget/{group}${uuid}', 'ApiController@widget');
Route::get('shortcut/{keyword}', 'ApiController@shortcut');
