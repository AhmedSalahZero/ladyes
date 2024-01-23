<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AreasController;
use App\Http\Controllers\Admin\CarMakeController;
use App\Http\Controllers\Admin\CarModelController;
use App\Http\Controllers\Admin\CitiesController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\DriversController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Helpers\AddInvitationCodeToController;
use App\Http\Controllers\Helpers\SendEmailMessageController;
use App\Http\Controllers\Helpers\SendVerificationCodeMessageByEmailController;
use App\Http\Controllers\Helpers\SendWhatsappMessageController;
use App\Http\Controllers\Helpers\SendWhatsappVerificationCodeController;
use App\Http\Controllers\Helpers\UpdateAreasBasedOnCity;
use App\Http\Controllers\Helpers\UpdateCitiesBasedOnCountry;
use App\Http\Controllers\Helpers\UpdateModelsBasedOnMake;
use App\Models\Admin;
use App\Notifications\Admins\AdminNotification;
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

    //###################### countries #########################
    Route::resource('notifications', NotificationsController::class)->only(['index']);

    //###################### messages #########################
    Route::post('send-whatsapp-message', [SendWhatsappMessageController::class, 'send'])->name('send.whatsapp.message');
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
