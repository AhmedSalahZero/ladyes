<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\OnbordingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RolesAndPermissionsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('test', function (Request $request) {
    dd($request->all());
});

Route::group(['namespace' => 'Admin', 'middleware' => 'auth:admin'], function () {
    Route::get('/dashboard', [DashboardController::class, 'getDashboard'])->name('dashboard.index');
    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
    //######################### profile ###############################
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('admin.update.profile');
    //###################### admins #########################

    //###################### car makes #########################

    //###################### slider #########################
    Route::get('/onbording', [OnbordingController::class, 'index'])->name('admin.onbording.index');
    Route::get('/create-onbording', [OnbordingController::class, 'create'])->name('admin.onbording.create');
    Route::post('/store-onbording', [OnbordingController::class, 'store'])->name('admin.onbording.store');
    Route::get('/edit-onbording/{id}', [OnbordingController::class, 'edit'])->name('admin.onbording.edit');
    Route::post('/update-onbording/{id}', [OnbordingController::class, 'update'])->name('admin.onbording.update');
    Route::post('/delete-onbording', [OnbordingController::class, 'delete'])->name('admin.onbording.delete');
    Route::post('/update-onbording-status', [OnbordingController::class, 'updateStatus'])->name('admin.onbording.update.status');
    //###################### users #########################
    Route::get('/users', [UsersController::class, 'index'])->name('admin.users.index');
    Route::get('/create-user', [UsersController::class, 'create'])->name('admin.users.create');
    Route::post('/store-user', [UsersController::class, 'store'])->name('admin.users.store');
    Route::get('/edit-user/{id}', [UsersController::class, 'edit'])->name('admin.users.edit');
    Route::get('/show-user/{id}', [UsersController::class, 'show'])->name('admin.users.show');
    Route::post('/update-user/{id}', [UsersController::class, 'update'])->name('admin.users.update');
    Route::post('/delete-user', [UsersController::class, 'delete'])->name('admin.users.delete');
    Route::put('/update-user-status', [UsersController::class, 'updateStatus'])->name('admin.users.update.status');
    //###################### users #########################
    // Route::get('/drivers',[DriverController::class,'index'])->name('admin.drivers.index');
    // Route::get('/create-driver',[DriverController::class,'create'])->name('admin.drivers.create');
    // Route::post('/store-driver',[DriverController::class,'store'])->name('admin.drivers.store');
    // Route::get('/edit-driver/{id}',[DriverController::class,'edit'])->name('admin.drivers.edit');
    // Route::post('/update-driver/{id}',[DriverController::class,'update'])->name('admin.drivers.update');
    // Route::post('/delete-driver',[DriverController::class,'delete'])->name('admin.drivers.delete');
    // Route::post('/update-driver-status',[DriverController::class,'updateStatus'])->name('admin.drivers.update.status');
    // Route::post('/country-cities',[DriverController::class,'getCountryCities'])->name('admin.drivers.country_cities');
    // Route::post('/cities-area',[DriverController::class,'getCityArea'])->name('admin.drivers.country_area');
    //##################### contactus ########################
    Route::get('/contact-us', [ContactUsController::class, 'index'])->name('admin.contacts.index');
    Route::get('/contact-us/{id}', [ContactUsController::class, 'show'])->name('admin.contacts.show');
    Route::post('/update-contact-us/{id}', [ContactUsController::class, 'update'])->name('admin.contacts.update');
    Route::post('/delete-contact', [ContactUsController::class, 'delete'])->name('admin.contacts.delete');
    //##################### settings ########################
    Route::get('/settings', [SettingsController::class, 'settings'])->name('admin.settings.edit');
    Route::post('/setting-update', [SettingsController::class, 'update'])->name('admin.settings.update');
    Route::post('/delete-file', [SettingsController::class, 'deleteFile'])->name('admin.delete.file');
    //####################### orders ##############################
    // Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    // Route::get('/show-order/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    // Route::post('/delete-order', [OrderController::class, 'delete'])->name('admin.orders.delete');
    // Route::post('/update-order-status', [OrderController::class, 'updateStatus'])->name('admin.orders.update_status');
    // Route::post('/send-order-user-email', [OrderController::class, 'sendUserEmail'])->name('admin.orders.send_user_email');
    // //###################### permission_group #########################

    Route::group(['prefix' => 'roles-and-permissions/', 'as' => 'roles.permissions.'], function () {
        Route::get('/index', [RolesAndPermissionsController::class, 'index'])->name('index');
        Route::get('/create', [RolesAndPermissionsController::class, 'create'])->name('create');
        Route::post('/store', [RolesAndPermissionsController::class, 'store'])->name('store');
        Route::get('/edit/{role}', [RolesAndPermissionsController::class, 'edit'])->name('edit');
        Route::put('/update/{role}', [RolesAndPermissionsController::class, 'update'])->name('update');
        Route::delete('/delete/{role}', [RolesAndPermissionsController::class, 'delete'])->name('delete');
    });

    //###################### coupons #########################
    Route::get('/coupons', [CouponsController::class, 'index'])->name('admin.coupons.index');
    Route::get('/create-coupon', [CouponsController::class, 'create'])->name('admin.coupons.create');
    Route::post('/store-coupon', [CouponsController::class, 'store'])->name('admin.coupons.store');
    Route::get('/edit-coupon/{id}', [CouponsController::class, 'edit'])->name('admin.coupons.edit');
    Route::post('/update-coupon/{id}', [CouponsController::class, 'update'])->name('admin.coupons.update');
    Route::post('/delete-coupon', [CouponsController::class, 'delete'])->name('admin.coupons.delete');
    //############################ chat ####################################
    Route::get('/chat/{id?}/{id2?}', [ChatController::class, 'index'])->name('admin.chat.index');
    Route::get('/chat-users', [ChatController::class, 'getUsersList'])->name('admin.chat.users');
    Route::get('/chat-user-messages/{id?}/{id2?}', [ChatController::class, 'getUserMessages'])->name('admin.chat.user_messages');
    Route::post('/chat-send', [ChatController::class, 'sendMessage'])->name('admin.chat.send');
});
Route::get('login', [LoginController::class, 'getLoginForm'])->name('get.admin.login');
Route::post('login', [LoginController::class, 'login'])->name('admin.login');
