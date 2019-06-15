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


Route::group(['prefix' => 'api'], function () {
    Route::post('login', 'SPAController@login');
    Route::post('register', 'SPAController@register');
    // pass message to server to broadcast to other user used in public chat
    Route::post('public', 'SPAController@sendMessage');
 //   Route::post('notify', 'SPAController@notify');
    // return al rooms that saved in database
    Route::get('rooms', 'RoomController@index');
});
// return single page for all requests.....
Route::get('/{any}', 'SPAController@Index')->where('any','.*');
