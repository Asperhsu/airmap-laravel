<?php
/**
 * Facebook Messenger Bot API
 */
use Illuminate\Http\Request;

Route::group(['prefix' => 'search'], function () {
    Route::get('site', 'FbBotController@searchSiteName'); 
    Route::get('region', 'FbBotController@searchRegion'); 
    Route::get('nearby', 'FbBotController@searchNearby'); 
});

Route::group(['prefix' => 'user'], function () {
    Route::get('favorite', 'FbBotController@listUserFavorite'); 

    // postback
    Route::get('add/{group}/{uuid}', 'FbBotController@addUserFavoriteSite')->name('bot.user.addsite'); 
    Route::get('add/{region}', 'FbBotController@addUserFavoriteRegion')->name('bot.user.addregion'); 
    Route::get('remove/{id}', 'FbBotController@removeUserFavorite')->name('bot.user.remove'); 
});