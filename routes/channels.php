<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
// retuern $user data form room chat .... that saved in currentUsersInRoom
Broadcast::channel('message.room.{id}', function ($user, $id) {
    return $user;
});
// use this for private chat
Broadcast::channel('message.{id}', function ($user, $id) {
    return $user;
});
// check the user it is auth or not...
// will return 403 error if uers not auth
Broadcast::channel('notifications.{user_id}', function ($user, $user_id) {
    return \Auth::user()->id == $user_id;
});

