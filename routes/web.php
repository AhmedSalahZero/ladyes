<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BonusesController;
use App\Http\Controllers\Admin\CancellationReasonsController;
use App\Http\Controllers\Admin\CarMakeController;
use App\Http\Controllers\Admin\CarModelController;
use App\Http\Controllers\Admin\CarSizesController;
use App\Http\Controllers\Admin\CitiesController;
use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\DepositsController;
use App\Http\Controllers\Admin\DriversController;
use App\Http\Controllers\Admin\EmergencyContactsController;
use App\Http\Controllers\Admin\FinesController;
use App\Http\Controllers\Admin\HelpsController;
use App\Http\Controllers\Admin\InformationController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\PromotionsController;
use App\Http\Controllers\Admin\RolesAndPermissionsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SupportTicketsController;
use App\Http\Controllers\Admin\TransactionsController;
use App\Http\Controllers\Admin\TravelConditionsController;
use App\Http\Controllers\Admin\TravelsController;
use App\Http\Controllers\Admin\WithdrawalsController;
use App\Http\Controllers\Helpers\AddInvitationCodeToController;
use App\Http\Controllers\Helpers\SendEmailMessageController;
use App\Http\Controllers\Helpers\SendSmsMessageController;
use App\Http\Controllers\Helpers\SendSmsVerificationCodeController;
use App\Http\Controllers\Helpers\SendVerificationCodeMessageByEmailController;
use App\Http\Controllers\Helpers\SendWhatsappMessageController;
use App\Http\Controllers\Helpers\SendWhatsappVerificationCodeController;
use App\Http\Controllers\Helpers\UpdateCitiesBasedOnCountry;
use App\Http\Controllers\Helpers\UpdateModelsBasedOnMake;
use App\Http\Controllers\Helpers\UpdateUsersBasedOnType;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect(\route('get.admin.login'));
})->name('site.home');
Route::get('/change-local/{lang}', function ($lang) {
    if (!in_array($lang, ['en', 'ar'])) {
        abort(404);
    }
    app()->setLocale($lang);
    session()->put('locale', $lang);

    return redirect()->back();
});

Route::get('make-migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate');
    \Illuminate\Support\Facades\Artisan::call('optimize');

    return 'migrated';
});

Route::group(['middleware' => 'auth:admin'], function () {
    //###################### admins #########################
	
	Route::group(['prefix' => 'roles-and-permissions/', 'as' => 'roles.permissions.'], function () {
		Route::get('/index', [RolesAndPermissionsController::class, 'index'])->name('index');
		Route::get('/create', [RolesAndPermissionsController::class, 'create'])->name('create');
		Route::post('/store', [RolesAndPermissionsController::class, 'store'])->name('store');
		Route::get('/edit/{role}', [RolesAndPermissionsController::class, 'edit'])->name('edit');
		Route::put('/update/{role}', [RolesAndPermissionsController::class, 'update'])->name('update');
		Route::delete('/delete/{role}', [RolesAndPermissionsController::class, 'delete'])->name('delete');
	});
	
    Route::resource('admins', AdminController::class);
    Route::put('admins-toggle-is-active', [AdminController::class, 'toggleIsActive'])->name('admins.toggle.is.active');
    //###################### travel conditions #########################
    Route::resource('travel-conditions', TravelConditionsController::class);
    Route::put('travels-toggle-is-active', [TravelConditionsController::class, 'toggleIsActive'])->name('travel-conditions.toggle.is.active');
	
    //###################### coupons #########################
	Route::resource('coupons',CouponsController::class);
	
	//###################### fines #########################
	Route::resource('fines',FinesController::class);
	Route::post('store-fine', [FinesController::class, 'storeFor'])->name('store.fine.for');
	
	//###################### bonuses #########################
	Route::resource('bonuses',BonusesController::class);
	Route::post('store-bonus', [BonusesController::class, 'storeFor'])->name('store.bonus.for');
	
		//###################### deposits #########################
		Route::resource('deposits',DepositsController::class);
		Route::post('store-deposit', [DepositsController::class, 'storeFor'])->name('store.deposit.for');
			//###################### withdrawals #########################
			Route::resource('withdrawals',WithdrawalsController::class);
			Route::post('store-withdrawal', [WithdrawalsController::class, 'storeFor'])->name('store.withdrawal.for');
			
	//###################### withdrawals #########################
	Route::resource('payments',PaymentsController::class);
	// Route::post('store-payment', [WithdrawalsController::class, 'storeFor'])->name('store.withdrawal.for');
	
    //###################### helps #########################
    Route::resource('helps', HelpsController::class);
    Route::put('helps-toggle-is-active', [HelpsController::class, 'toggleIsActive'])->name('helps.toggle.is.active');

    //###################### information #########################
    Route::resource('information', InformationController::class);
    Route::put('information-toggle-is-active', [InformationController::class, 'toggleIsActive'])->name('information.toggle.is.active');

    //###################### travel conditions #########################
    Route::resource('cancellation-reasons', CancellationReasonsController::class);
    Route::put('cancellations-reasons-toggle-is-active', [CancellationReasonsController::class, 'toggleIsActive'])->name('cancellation-reasons.toggle.is.active');
    //###################### travel conditions #########################
    Route::post('emergency-contacts/attach', [EmergencyContactsController::class, 'attach'])->name('emergency-contacts.attach');
    Route::delete('emergency-contacts/detach', [EmergencyContactsController::class, 'detach'])->name('detach.modal.emergency-contacts');
    Route::resource('emergency-contacts', EmergencyContactsController::class);
    Route::put('emergency-contacts-toggle-can-receive-travel-infos', [EmergencyContactsController::class, 'toggleCanReceiveTravelInfo'])->name('emergency-contacts.toggle.can.receive.travel.infos');

    //###################### promotions #########################
    Route::resource('promotions', PromotionsController::class);
    //###################### sliders #########################
    Route::resource('sliders', SliderController::class);
    //###################### car makes #########################
    Route::resource('car-makes', CarMakeController::class);
    //###################### car makes #########################
    Route::resource('car-models', CarModelController::class);
    //###################### drivers #########################
    Route::put('drivers/toggle-is-banned', [DriversController::class, 'toggleIsBanned'])->name('driver.toggle.is.banned');
    Route::put('drivers/toggle-is-verified', [DriversController::class, 'toggleIsVerified'])->name('driver.toggle.is.verified');
    Route::resource('drivers', DriversController::class);
    //###################### clients #########################
    Route::put('clients/toggle-is-banned', [ClientsController::class, 'toggleIsBanned'])->name('client.toggle.is.banned');
    Route::put('clients/toggle-is-verified', [ClientsController::class, 'toggleIsVerified'])->name('client.toggle.is.verified');
    Route::resource('clients', ClientsController::class);
	
    //###################### transactions #########################
    Route::resource('transactions', TransactionsController::class)->only(['index']);	
    //###################### travel #########################
    Route::resource('travels', TravelsController::class)->only(['index']);
	Route::get('travels/cancelled',[TravelsController::class,'showCancelled'])->name('cancelled.travels.index');
	Route::get('travels/on-the-way',[TravelsController::class,'showOnTheWay'])->name('on.the.way.travels.index');
	Route::get('travels/completed',[TravelsController::class,'showCompleted'])->name('completed.travels.index');
	
	
	//###################### car sizes #########################
	Route::patch('update-car-sizes-prices/{carSize}',[CarSizesController::class , 'updatePrices'])->name('car-sizes.update.prices');
	Route::resource('car-sizes', CarSizesController::class)->only(['index','edit','update']);
	Route::resource('support-tickets', SupportTicketsController::class)->only(['index']);
	
    //###################### countries #########################
	// Route::patch('update-countries/{carSize}',[CarSizesController::class , 'updatePrices'])->name('car-sizes.update.prices');
    Route::resource('countries', CountriesController::class)->only(['index','update']);
	
	  

    //###################### cities #########################
    Route::resource('cities', CitiesController::class);

    //###################### areas #########################
    // Route::resource('areas', AreasController::class);

    //###################### settings #########################
    Route::resource('settings', SettingsController::class)->only(['create', 'store']);
    //###################### app guidelines #########################
    Route::get('app-guidelines/create', [SettingsController::class, 'createAppGuidelines'])->name('app-guidelines.create');
    Route::post('app-guidelines/create', [SettingsController::class, 'storeAppGuidelines'])->name('app-guidelines.store');
	
	    //###################### app text #########################
		// ترجمة  نصوص التطبيق
		Route::get('app-text/create', [SettingsController::class, 'createAppText'])->name('app-text.create');
		Route::post('app-text/create', [SettingsController::class, 'storeAppText'])->name('app-text.store');

    //###################### notifications #########################
    Route::get('admin-notifications', [NotificationsController::class,'viewAdminNotifications'])->name('admin.notifications.index');
    Route::get('app-notifications', [NotificationsController::class,'viewAppNotifications'])->name('app.notifications.index');
    Route::get('app-notifications/create', [NotificationsController::class,'createAppNotifications'])->name('app.notifications.create');
    Route::post('app-notifications/create', [NotificationsController::class,'storeAppNotifications'])->name('app.notifications.store');

    //###################### messages #########################
    Route::post('send-sms-message', [SendSmsMessageController::class, 'send'])->name('send.sms.message');
    Route::post('send-whatsapp-message', [SendWhatsappMessageController::class, 'send'])->name('send.whatsapp.message');
    Route::post('send-sms-verification-code', [SendSmsVerificationCodeController::class, 'send'])->name('send.verification.code.through.sms');
    Route::post('send-whatsapp-verification-code', [SendWhatsappVerificationCodeController::class, 'send'])->name('send.verification.code.through.whatsapp');
    Route::post('send-email-message', [SendEmailMessageController::class, 'send'])->name('send.email.message');
    Route::post('send-verification-code-email-message', [SendVerificationCodeMessageByEmailController::class, 'send'])->name('send.verification.code.through.email');
    Route::post('add-invitation-code-to-driver', [AddInvitationCodeToController::class, 'attach'])->name('add.invitation.code.to.driver');
});
Route::prefix('helpers')->group(function () {
    Route::get('update-cities-based-on-country', [UpdateCitiesBasedOnCountry::class, '_invoke'])->name('update.cities.based.on.country');
    Route::get('update-users-based-on-model-type', [UpdateUsersBasedOnType::class, '_invoke'])->name('update.users.based.on.model.type');
    // Route::get('update-areas-based-on-city', [UpdateAreasBasedOnCity::class, '_invoke'])->name('update.areas.based.on.city');
    Route::get('update-models-based-on-make', [UpdateModelsBasedOnMake::class, '_invoke'])->name('update.models.based.on.make');
    Route::put('mark-notifications-as-read/{admin}', [NotificationsController::class, 'markAsRead'])->name('mark.notifications.as.read');
    ;
});



// Route::get('test-notification',function(){
// 	$admin = Admin::first();
// 	$admin->notify(new AdminNotification('title','نص','message here','نص الرسالة',formatForView(now())));
// });
