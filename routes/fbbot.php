<?php
/**
 * Facebook Messenger Bot API
 */
use Illuminate\Http\Request;

Route::group(['prefix' => 'search'], function () {
    Route::get('site', 'FbBotController@searchSiteName'); 
    Route::get('region', 'FbBotController@searchRegion'); 
});

Route::group(['prefix' => 'user'], function () {
    Route::get('favorite', 'FbBotController@listUserFavorite'); 
    Route::get('add/{group}/{name}', 'FbBotController@addUserFavoriteSite')->name('bot.user.addsite'); 
    Route::get('add/{region}', 'FbBotController@addUserFavoriteRegion')->name('bot.user.addregion'); 
    Route::get('remove/{id}', 'FbBotController@removeUserFavorite')->name('bot.user.remove'); 
});