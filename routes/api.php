<?php

use App\Http\Controllers\Api\AddressesController;
use App\Http\Controllers\Api\CitiesController;
use App\Http\Controllers\Api\Client\Auth\AuthController as ClientAuthController;
use App\Http\Controllers\Api\Client\ClientsController;
use App\Http\Controllers\Api\Client\CouponsController;
use App\Http\Controllers\Api\Client\RatingController;
use App\Http\Controllers\Api\Client\TravelsController;
use App\Http\Controllers\Api\CountriesController;
use App\Http\Controllers\Api\Driver\Auth\AuthController as DriverAuthController;
use App\Http\Controllers\Api\Driver\CancellationReasonsController;
use App\Http\Controllers\Api\Driver\CarMakeController;
use App\Http\Controllers\Api\Driver\CarModelController;
use App\Http\Controllers\Api\Driver\CarSizesController;
use App\Http\Controllers\Api\Driver\DriversController;
use App\Http\Controllers\Api\Driver\GuidelinesController;
use App\Http\Controllers\Api\Driver\HelpController;
use App\Http\Controllers\Api\Driver\InformationController;
use App\Http\Controllers\Api\Driver\PromotionsController;
use App\Http\Controllers\Api\Driver\RatingController as DriverRatingController;
use App\Http\Controllers\Api\Driver\TravelConditionController;
use App\Http\Controllers\Api\Driver\TravelsController as DriverTravelsController;
use App\Http\Controllers\Api\EmergencyContactsController;
use App\Http\Controllers\Api\MyWalletController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\PaymentMethodsController;
use App\Http\Controllers\Helpers\Apis\SendEmailMessageController;
use App\Http\Controllers\Helpers\Apis\SendSmsMessageController;
use App\Http\Controllers\Helpers\Apis\SendWhatsappMessageController;
use App\Services\Payments\MyFatoorahService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function(){
	
	
Route::prefix('utilities')->group(function () {
    Route::post('send-whatsapp-message', [SendWhatsappMessageController::class, 'send']);
    Route::post('send-sms-message', [SendSmsMessageController::class, 'send']);
    Route::post('send-email-message', [SendEmailMessageController::class, 'send']);
    Route::post('resend-verification-code-by-sms', [SendSmsMessageController::class, 'resendVerificationCode']);
    Route::post('resend-verification-code-by-email', [SendEmailMessageController::class, 'resendVerificationCode']);
    Route::post('resend-verification-code-by-whatsapp', [SendWhatsappMessageController::class, 'resendVerificationCode']);
});
/**
 * * سواء كان مسجل دخولة كعميل او سائق
 */
Route::middleware('authClientOrDriver')->group(function(){
	Route::apiResource('emergency-contacts', EmergencyContactsController::class);
	Route::get('my-transactions',[MyWalletController::class,'index']);
	Route::get('my-notifications',[NotificationsController::class,'index']);
	Route::delete('delete-my-notifications',[NotificationsController::class,'delete']);
});

Route::prefix('drivers')->group(function () {
    Route::prefix('auth')->group(function () {
		Route::post('send-verification-code', [DriverAuthController::class, 'sendVerificationCode']);
        Route::post('verify-verification-code', [DriverAuthController::class, 'verifyVerificationCode']);
        Route::post('register', [DriverAuthController::class, 'register']);
        Route::post('login', [DriverAuthController::class, 'login']);
        Route::post('logout', [DriverAuthController::class, 'logout'])->middleware('auth:driver');
    });
    Route::middleware('auth:driver')->group(function () {
        Route::get('show', [DriversController::class, 'show']);
        Route::put('update', [DriversController::class, 'update']);
        Route::get('information', [InformationController::class, 'viewAfterSignupMessage']);
        Route::get('help', [HelpController::class, 'viewForDriver']);
		Route::apiResource('travel-conditions',TravelConditionController::class);
		Route::apiResource('cancellation-reasons',CancellationReasonsController::class);
		Route::apiResource('car-sizes',CarSizesController::class);
		Route::apiResource('car-makes',CarMakeController::class);
		Route::apiResource('car-models',CarModelController::class);
		Route::apiResource('promotions',PromotionsController::class);
		Route::post('rating',[DriverRatingController::class,'rateWith']);
		Route::patch('travels/{travel}/mark-as-started',[DriverTravelsController::class,'markAsStarted']);
		Route::patch('travels/{travel}/mark-as-cancelled',[DriverTravelsController::class,'markAsCancelled']);
		Route::patch('travels/{travel}/mark-as-completed',[DriverTravelsController::class,'markAsCompleted']);
		
    });
});





/**
 * * خاصة بالعميل فقط
 */
Route::prefix('clients')->group(function () {
	Route::prefix('auth')->group(function () {
        Route::post('send-verification-code', [ClientAuthController::class, 'sendVerificationCode']);
        Route::post('verify-verification-code', [ClientAuthController::class, 'verifyVerificationCode']);
		Route::post('register', [ClientAuthController::class, 'register']);
        Route::post('logout', [ClientAuthController::class, 'logout'])->middleware('auth:client');
    });
    Route::middleware('auth:client')->group(function () {
		Route::get('show', [ClientsController::class, 'show']);
        Route::put('update', [ClientsController::class, 'update']);
        Route::get('information', [InformationController::class, 'viewAfterSignupMessage']);
        Route::get('help', [HelpController::class, 'viewForDriver']);
		Route::apiResource('travel-conditions',TravelConditionController::class);
		Route::apiResource('cancellation-reasons',CancellationReasonsController::class);
		Route::apiResource('coupons',CouponsController::class);
		Route::post('rating',[RatingController::class,'rateWith']);
		Route::apiResource('addresses', AddressesController::class);
		Route::get('travels/available-drivers',[TravelsController::class,'getAvailableDriverForTravels']);
		Route::get('travels/get-distance-and-duration-between-client-and-driver',[TravelsController::class,'getDistanceAndDurationBetweenClientAndDriver']);
		Route::get('travels/show-available-car-sizes',[TravelsController::class,'getAvailableCarSizes']);
		Route::apiResource('travels', TravelsController::class);
		Route::patch('travels/{travel}/mark-as-cancelled',[TravelsController::class,'markAsCancelled']);
		Route::post('travels/{travel}/store-payment',[TravelsController::class,'storePayment']);
		Route::post('travels/{travel}/send-arrival-notification-to-client',[TravelsController::class,'sendArrivalNotificationToClient']);
		Route::get('travels/{travel}/price-details' , [TravelsController::class,'getPriceDetails']);
		
    });
});


/**
 * * عامة .. اي لا تحتاج الي تسجيل دخول
 */
Route::apiResource('countries',CountriesController::class);
Route::apiResource('cities',CitiesController::class);
Route::apiResource('payment-methods',PaymentMethodsController::class);
Route::get('guidelines', [GuidelinesController::class, 'view']);
});
Route::post('store-payment',function(Request $request){
	$myfatoorah = new MyFatoorahService;
	return 
	$myfatoorah->storeNewOrder(100,1,'product name here',2,'ahmed salah','1025894984','asalahdev5@gmail.com');
});
