<?php

use App\Http\Controllers\Admin\Api\AuthApiController;
use App\Http\Controllers\Admin\Api\SystemApiController;
use App\Http\Controllers\vendor\Chatify\Api\MessagesController;
use Illuminate\Support\Facades\Route;

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

############################## auth ######################
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register-user', [AuthApiController::class, 'registerUser']);
Route::post('/register-driver', [AuthApiController::class, 'registerDriver']);
Route::post('/check-verification-code', [SystemApiController::class, 'checkVerificationCode']);
Route::post('/resend-verification-code', [SystemApiController::class, 'resendVerificationCode']);

Route::get('/cars-models', [SystemApiController::class, 'carsModels']);
Route::get('/countries', [SystemApiController::class, 'getCountries']);
Route::post('/country-cities', [SystemApiController::class, 'countryCities']);
Route::post('/city-areas', [SystemApiController::class, 'cityAreas']);

Route::get('/sliders', [SystemApiController::class, 'sliders']);
Route::get('/settings', [SystemApiController::class, 'settings']);
Route::get('/in-bording', [SystemApiController::class, 'inBordingData']);



Route::group(['middleware' => 'JwtMiddleware'], function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    ################### profile ###########################
    Route::post('/update-user-profile', [SystemApiController::class, 'updateUserProfile']);
    Route::get('/user-data', [SystemApiController::class, 'userData']);
    Route::get('/driver-data', [SystemApiController::class, 'driverData']);
    Route::post('/store-contact', [SystemApiController::class, 'storeContact']);
    Route::get('/notifications-list',[SystemApiController::class,'notificationsList']);
    Route::post('/delete-account',[SystemApiController::class,'deleteAccount']);
    Route::get('/notifications-list',[SystemApiController::class,'getNotifacationList']);
    Route::get('/reasons-list',[SystemApiController::class,'reasonsList']);
    ############### address ###############3
    Route::get('/address',[SystemApiController::class,'getAddress']);
    Route::post('/create-address',[SystemApiController::class,'createAddress']);
    #################### cards ###############3
    Route::get('user-cards',[SystemApiController::class,'userCards']);
    Route::post('add-user-card',[SystemApiController::class,'addUserCard']);
    Route::post('delete-card',[SystemApiController::class,'deleteCard']);
    ################### order #######################
    Route::post('make-transaction',[SystemApiController::class,'makeTransaction']);
    Route::post('create-order',[SystemApiController::class,'createOrder']);
    Route::post('track-order',[SystemApiController::class,'trackOrder']);
    Route::post('user-orders',[SystemApiController::class,'userOrders']);
    Route::post('order-details',[SystemApiController::class,'getOrderDetails']);
    Route::post('change-order-status',[SystemApiController::class,'changeOrderStatus']);
    ################## chat ###################
    Route::post('/chat/auth', [MessagesController::class,'pusherAuth'])->name('api.pusher.auth');
    Route::post('/send-message', [MessagesController::class,'send'])->name('api.send.message');
    Route::post('/fetch-messages', [MessagesController::class,'fetch'])->name('api.fetch.messages');
    Route::get('/download/{fileName}', [MessagesController::class,'download'])->name('api.'.config('chatify.attachments.download_route_name'));
    Route::post('/make-seen', [MessagesController::class,'seen'])->name('api.messages.seen');
    Route::get('/get-contacts', [MessagesController::class,'getContacts'])->name('api.contacts.get');
    Route::post('/favorite', [MessagesController::class,'favorite'])->name('api.star');
    Route::post('/favorites', [MessagesController::class,'getFavorites'])->name('api.favorites');
    Route::post('/delete-conversation', [MessagesController::class,'deleteConversation'])->name('api.conversation.delete');
    Route::post('/set-active-status', [MessagesController::class,'setActiveStatus'])->name('api.activeStatus.set');

});
