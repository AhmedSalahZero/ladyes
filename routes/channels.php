<?php

use Illuminate\Http\Request;
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


Broadcast::channel('App.Models.Admin.{id}', function ($admin, $id) {
	return true ;
});

Broadcast::channel('App.Models.Client.{id}', function ($client, $id) {
	return true ;
});

Broadcast::channel('App.Models.Driver.{id}', function ($driver, $id) {
	return true ;
});

Broadcast::channel('client.notifications.{id}', function ($user,$id) {
	return true ;
});
Broadcast::channel('driver.new.travel.available.notifications.{id}', function ($user,$id) {
	return true ;
});
