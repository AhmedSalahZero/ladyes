<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CancellationReasonsController;
use App\Http\Controllers\Admin\CarMakeController;
use App\Http\Controllers\Admin\CarModelController;
use App\Http\Controllers\Admin\CitiesController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\DriversController;
use App\Http\Controllers\Admin\EmergencyContactsController;
use App\Http\Controllers\Admin\HelpsController;
use App\Http\Controllers\Admin\InformationController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TravelConditionsController;
use App\Http\Controllers\Helpers\AddInvitationCodeToController;
use App\Http\Controllers\Helpers\SendEmailMessageController;
use App\Http\Controllers\Helpers\SendSmsMessageController;
use App\Http\Controllers\Helpers\SendSmsVerificationCodeController;
use App\Http\Controllers\Helpers\SendVerificationCodeMessageByEmailController;
use App\Http\Controllers\Helpers\SendWhatsappMessageController;
use App\Http\Controllers\Helpers\SendWhatsappVerificationCodeController;
use App\Http\Controllers\Helpers\UpdateCitiesBasedOnCountry;
use App\Http\Controllers\Helpers\UpdateModelsBasedOnMake;
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
    Route::resource('admins', AdminController::class);
    Route::put('admins-toggle-is-active', [AdminController::class, 'toggleIsActive'])->name('admins.toggle.is.active');
    //###################### travel conditions #########################
    Route::resource('travel-conditions', TravelConditionsController::class);
    Route::put('travels-toggle-is-active', [TravelConditionsController::class, 'toggleIsActive'])->name('travel-conditions.toggle.is.active');
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
    Route::resource('promotions', PromotionController::class);
    Route::put('promotions-toggle-is-active', [PromotionController::class, 'toggleIsActive'])->name('promotions.toggle.is.active');

    //###################### car makes #########################
    Route::resource('car-makes', CarMakeController::class);
    //###################### car makes #########################
    Route::resource('car-models', CarModelController::class);
    //###################### drivers #########################
    Route::put('drivers/toggle-is-banned', [DriversController::class, 'toggleIsBanned'])->name('driver.toggle.is.banned');
    Route::put('drivers/toggle-is-verified', [DriversController::class, 'toggleIsVerified'])->name('driver.toggle.is.verified');
    Route::resource('drivers', DriversController::class);
    //###################### countries #########################
    Route::resource('countries', CountriesController::class)->only(['index']);

    //###################### cities #########################
    Route::resource('cities', CitiesController::class);

    //###################### areas #########################
    // Route::resource('areas', AreasController::class);

    //###################### settings #########################
    Route::resource('settings', SettingsController::class)->only(['create', 'store']);
    //###################### app guidelines #########################
    Route::get('app-guidelines/create', [SettingsController::class, 'createAppGuidelines'])->name('app-guidelines.create');
    Route::post('app-guidelines/create', [SettingsController::class, 'storeAppGuidelines'])->name('app-guidelines.store');

    //###################### countries #########################
    Route::resource('notifications', NotificationsController::class)->only(['index']);

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
    // Route::get('update-areas-based-on-city', [UpdateAreasBasedOnCity::class, '_invoke'])->name('update.areas.based.on.city');
    Route::get('update-models-based-on-make', [UpdateModelsBasedOnMake::class, '_invoke'])->name('update.models.based.on.make');
    Route::put('mark-notifications-as-read/{admin}', [NotificationsController::class, 'markAsRead'])->name('mark.notifications.as.read');
    ;
});
// Route::get('test-notification',function(){
// 	$admin = Admin::first();
// 	$admin->notify(new AdminNotification('title','نص','message here','نص الرسالة',formatForView(now())));
// });
