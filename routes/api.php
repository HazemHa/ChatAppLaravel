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
// any user   must be auth to can access here
Route::middleware('auth:api')->group(function(){
    // return info for auth user
    Route::get('/user', function (Request $request) {
        return ['user'=>$request->user(),'token'=>$request->user()->remember_token];
    });

  //  Route::get('/friends', 'UserController@friends');
  // get all message which saved in database (private,room)
    Route::get('/message/{to}/{type}', 'MessageController@MessageUser');
    // get all notificaitons for user , he must be auth to can access here
    Route::get('mynotification', 'UserController@mynotification');
    //udpate profile user
    Route::post('user/{id}', 'UserController@update');
    //send notify for request friend to another user
    Route::post('notifyRequestFriend', 'FriendController@notifyRequestFriend');
    // send notify for user who send request friend , which request it is accepted
    Route::post('notifyAcceptFriend', 'FriendController@notifyAcceptFriend');
     // make notification  seen => updated it status
    Route::put('seenNotification/{id}', 'FriendController@seenNotification');
    //block user to prevent him to chat with you in private just. ..
    Route::post('blockUser', 'FriendController@blockUser');
      // show -> get -> index, delete ->delete -> delete , update -> put -> update
    Route::resources([
       // 'user'=> 'UserController',
        'message'=> 'MessageController',
        'friend' => 'FriendController',
        'room' => 'RoomController']
    );
    
});
