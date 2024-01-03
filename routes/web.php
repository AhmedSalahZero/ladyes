<?php

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

Route::get('/', function (){
  return redirect(\route('get.admin.login'));
})->name('site.home');
Route::get('/change-local/{lang}',function ($lang){
    if (!in_array($lang, ['en', 'ar'])) {
        abort(404);
    }
    app()->setLocale($lang);
    session()->put('locale',$lang);
    return redirect()->back();
});

Route::get('make-migrate',function (){
    \Illuminate\Support\Facades\Artisan::call('migrate');
    \Illuminate\Support\Facades\Artisan::call('optimize');
    return 'migrated';
});
