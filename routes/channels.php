<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('one-to-one-message', function($user){
    return !is_null($user);
});


//presence chanell
Broadcast::channel('track-message-channel', function($user){
    return $user;
});

Broadcast::channel('status-update',function($user){
    return $user;
});

Broadcast::channel('broadcast-message', function($user){
    return $user;
});

//message-deleted
Broadcast::channel('message-deleted', function($user){
    return $user;
});

Broadcast::channel('message-updated', function($user){
    return $user;
});
