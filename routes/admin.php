<?php


use App\Http\Controllers\Admin\AdditionServicesController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CarMakeController;
use App\Http\Controllers\Admin\CarModelController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\CitiesController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CountriesCitiesController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\OnbordingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReasonController;
use App\Http\Controllers\Admin\RolesAndPermissionsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::post('test',function(Request $request){
	dd($request->all());
});



Route::group(['namespace' => 'Admin', 'middleware' => 'auth:admin'], function () {
    Route::get('/dashboard',[DashboardController::class,'getDashboard'])->name('dashboard.index');
    Route::post('/logout',[LoginController::class,'logout'])->name('admin.logout');
    ########################## profile ###############################
    Route::get('/profile',[AdminController::class,'profile'])->name('admin.profile');
    Route::post('/update-profile',[AdminController::class,'updateProfile'])->name('admin.update.profile');
    ####################### admins #########################

    // Route::get('/admins',[AdminController::class,'index'])->name('admin.admins.index');
    // Route::get('/admins/create',[AdminController::class,'create'])->name('admin.admins.create');
    // Route::post('/admins/store',[AdminController::class,'store'])->name('admin.admins.store');
    // Route::get('admins/{admin}/edit',[AdminController::class,'edit'])->name('admin.admins.edit');
    // Route::put('/admins/{admin}/update',[AdminController::class,'update'])->name('admin.admins.update');
    // Route::delete('admins/delete/{admin}',[AdminController::class,'delete'])->name('admin.admins.delete');
    // Route::post('/store-emp-admin',[AdminController::class,'storeEmployee'])->name('store.emp.admin');
    // Route::post('/edit-emp-admin',[AdminController::class,'editEmployee'])->name('edit.emp.admin');
    ####################### car makes #########################
	
	
     ####################### slider #########################
    Route::get('/onbording',[OnbordingController::class,'index'])->name('admin.onbording.index');
    Route::get('/create-onbording',[OnbordingController::class,'create'])->name('admin.onbording.create');
    Route::post('/store-onbording',[OnbordingController::class,'store'])->name('admin.onbording.store');
    Route::get('/edit-onbording/{id}',[OnbordingController::class,'edit'])->name('admin.onbording.edit');
    Route::post('/update-onbording/{id}',[OnbordingController::class,'update'])->name('admin.onbording.update');
    Route::post('/delete-onbording',[OnbordingController::class,'delete'])->name('admin.onbording.delete');
    Route::post('/update-onbording-status',[OnbordingController::class,'updateStatus'])->name('admin.onbording.update.status');
    ####################### users #########################
    Route::get('/users',[UsersController::class,'index'])->name('admin.users.index');
    Route::get('/create-user',[UsersController::class,'create'])->name('admin.users.create');
    Route::post('/store-user',[UsersController::class,'store'])->name('admin.users.store');
    Route::get('/edit-user/{id}',[UsersController::class,'edit'])->name('admin.users.edit');
    Route::get('/show-user/{id}',[UsersController::class,'show'])->name('admin.users.show');
    Route::post('/update-user/{id}',[UsersController::class,'update'])->name('admin.users.update');
    Route::post('/delete-user',[UsersController::class,'delete'])->name('admin.users.delete');
    Route::put('/update-user-status',[UsersController::class,'updateStatus'])->name('admin.users.update.status');
    ####################### users #########################
    // Route::get('/drivers',[DriverController::class,'index'])->name('admin.drivers.index');
    // Route::get('/create-driver',[DriverController::class,'create'])->name('admin.drivers.create');
    // Route::post('/store-driver',[DriverController::class,'store'])->name('admin.drivers.store');
    // Route::get('/edit-driver/{id}',[DriverController::class,'edit'])->name('admin.drivers.edit');
    // Route::post('/update-driver/{id}',[DriverController::class,'update'])->name('admin.drivers.update');
    // Route::post('/delete-driver',[DriverController::class,'delete'])->name('admin.drivers.delete');
    // Route::post('/update-driver-status',[DriverController::class,'updateStatus'])->name('admin.drivers.update.status');
    // Route::post('/country-cities',[DriverController::class,'getCountryCities'])->name('admin.drivers.country_cities');
    // Route::post('/cities-area',[DriverController::class,'getCityArea'])->name('admin.drivers.country_area');
    ###################### contactus ########################
    Route::get('/contact-us',[ContactUsController::class,'index'])->name('admin.contacts.index');
    Route::get('/contact-us/{id}',[ContactUsController::class,'show'])->name('admin.contacts.show');
    Route::post('/update-contact-us/{id}',[ContactUsController::class,'update'])->name('admin.contacts.update');
    Route::post('/delete-contact',[ContactUsController::class,'delete'])->name('admin.contacts.delete');
    ###################### settings ########################
    Route::get('/settings',[SettingsController::class,'settings'])->name('admin.settings.edit');
    Route::post('/setting-update',[SettingsController::class,'update'])->name('admin.settings.update');
    Route::post('/delete-file',[SettingsController::class,'deleteFile'])->name('admin.delete.file');
    ######################## orders ##############################
    Route::get('/orders',[OrderController::class,'index'])->name('admin.orders.index');
    Route::get('/show-order/{id}',[OrderController::class,'show'])->name('admin.orders.show');
    Route::post('/delete-order',[OrderController::class,'delete'])->name('admin.orders.delete');
    Route::post('/update-order-status',[OrderController::class,'updateStatus'])->name('admin.orders.update_status');
    Route::post('/send-order-user-email',[OrderController::class,'sendUserEmail'])->name('admin.orders.send_user_email');
    ####################### permission_group #########################
	
	Route::group(['prefix' => 'roles-and-permissions/', 'as' => 'roles.permissions.'], function () {
		Route::get('/index', [RolesAndPermissionsController::class , 'index'])->name('index');
		Route::get('/create',[RolesAndPermissionsController::class,'create' ])->name('create');
		Route::post('/store', [RolesAndPermissionsController::class , 'store'])->name('store');
		Route::get('/edit/{role}',[RolesAndPermissionsController::class ,'edit'])->name('edit');
		Route::put('/update/{role}', [RolesAndPermissionsController::class , 'update'])->name('update');
		Route::delete('/delete/{role}', [RolesAndPermissionsController::class , 'delete'])->name('delete');
	});
	
    // Route::get('/permission-groups',[PermitionGroupController::class,'index'])->name('admin.permission_group.index');
    // Route::get('/create-permission-group',[PermitionGroupController::class,'create'])->name('admin.permission_group.create');
    // Route::post('/store-permission-group',[PermitionGroupController::class,'store'])->name('admin.permission_group.store');
    // Route::get('/edit-permission-group/{id}',[PermitionGroupController::class,'edit'])->name('admin.permission_group.edit');
    // Route::post('/update-permission-group/{id}',[PermitionGroupController::class,'update'])->name('admin.permission_group.update');
    // Route::post('/delete-permission-group',[PermitionGroupController::class,'delete'])->name('admin.permission_group.delete');
    // ####################### reasons #########################
    Route::get('/reasons',[ReasonController::class,'index'])->name('admin.reasons.index');
    Route::get('/create-reason',[ReasonController::class,'create'])->name('admin.reasons.create');
    Route::post('/store-reason',[ReasonController::class,'store'])->name('admin.reasons.store');
    Route::get('/edit-reason/{id}',[ReasonController::class,'edit'])->name('admin.reasons.edit');
    Route::post('/update-reason/{id}',[ReasonController::class,'update'])->name('admin.reasons.update');
    Route::post('/delete-reason',[ReasonController::class,'delete'])->name('admin.reasons.delete');
     ####################### cars_models #########################
    // Route::get('/cars-models',[CarsModelController::class,'index'])->name('admin.cars_models.index');
    // Route::get('/create-cars-model',[CarsModelController::class,'create'])->name('admin.cars_models.create');
    // Route::post('/store-cars-model',[CarsModelController::class,'store'])->name('admin.cars_models.store');
    // Route::get('/edit-cars-model/{id}',[CarsModelController::class,'edit'])->name('admin.cars_models.edit');
    // Route::post('/update-cars-model/{id}',[CarsModelController::class,'update'])->name('admin.cars_models.update');
    // Route::post('/delete-cars-model',[CarsModelController::class,'delete'])->name('admin.cars_models.delete');
    // Route::post('/update-status-cars-model',[CarsModelController::class,'updateStatus'])->name('admin.cars_models.update.status');
    ####################### countries #########################
    // Route::get('/countries',[CountriesCitiesController::class,'index'])->name('admin.countries.index');
    // Route::get('/create-country',[CountriesCitiesController::class,'create'])->name('admin.countries.create');
    // Route::post('/store-country',[CountriesCitiesController::class,'store'])->name('admin.countries.store');
    // Route::get('/edit-country/{id}',[CountriesCitiesController::class,'edit'])->name('admin.countries.edit');
    // Route::get('/city-areas/{id}',[CountriesCitiesController::class,'areas'])->name('admin.countries.areas');
    // Route::post('/update-country/{id}',[CountriesCitiesController::class,'update'])->name('admin.countries.update');
    // Route::post('/delete-country',[CountriesCitiesController::class,'delete'])->name('admin.countries.delete');
    // Route::post('/country-add-city',[CountriesCitiesController::class,'addCity'])->name('admin.countries.add_city');
    // Route::post('/country-add-area',[CountriesCitiesController::class,'addArea'])->name('admin.countries.add_area');
    ####################### coupons #########################
    Route::get('/coupons',[CouponsController::class,'index'])->name('admin.coupons.index');
    Route::get('/create-coupon',[CouponsController::class,'create'])->name('admin.coupons.create');
    Route::post('/store-coupon',[CouponsController::class,'store'])->name('admin.coupons.store');
    Route::get('/edit-coupon/{id}',[CouponsController::class,'edit'])->name('admin.coupons.edit');
    Route::post('/update-coupon/{id}',[CouponsController::class,'update'])->name('admin.coupons.update');
    Route::post('/delete-coupon',[CouponsController::class,'delete'])->name('admin.coupons.delete');
    ############################# chat ####################################
    Route::get('/chat/{id?}/{id2?}',[ChatController::class,'index'])->name('admin.chat.index');
    Route::get('/chat-users',[ChatController::class,'getUsersList'])->name('admin.chat.users');
    Route::get('/chat-user-messages/{id?}/{id2?}',[ChatController::class,'getUserMessages'])->name('admin.chat.user_messages');
    Route::post('/chat-send',[ChatController::class,'sendMessage'])->name('admin.chat.send');

});
Route::get('login',[LoginController::class,'getLoginForm'])->name('get.admin.login');
Route::post('login',[LoginController::class,'login'])->name('admin.login');
