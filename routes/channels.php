<?php

use App\Models\UserInterface;
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

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return true;;
//     return (int) $user->id === (int) $id;
// });


Broadcast::channel('roomalert.{id}', function (UserInterface $user,$id) {

    // return true;;
    return (int) $user->id === (int) $id;
});
