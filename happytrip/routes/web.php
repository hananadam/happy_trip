<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HotelsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('test', [HotelsController::class, 'test']);

Auth::routes();
Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('logout', [UserController::class, 'logout'])->name('logout');

Route::get('/', [PagesController::class, 'showWelcome'])->name('welcome');
Route::get('/about', [PagesController::class, 'showAbout'])->name('about');
Route::get('/get_currency', [PagesController::class, 'convertCurrency'])->name('get_currency');

Route::group([
    'prefix' => 'customer-service',
    'name' => 'customer-service.',
    'as' => 'customer-service.',
], function () {
    Route::get('/contact-us', [PagesController::class, 'showContactUs'])->name('contact_us');
    Route::get('/faq', [PagesController::class, 'showFaq'])->name('faq');
});

Route::get('setlocale/{locale}', function ($locale) {
    if (in_array($locale, \Config::get('app.locales'))) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
});
Route::group([
    'prefix' => 'hotels',
    'name' => 'hotels.',
    'as' => 'hotels.',
], function () {
    Route::get('/result/{search}', [HotelsController::class, 'showSearchResults'])->name('search');
    Route::get('/view/{hotelCode}', [HotelsController::class, 'showHotelDetails'])->name('hotelDetails');
});

Route::get('/payment', [PaymentController::class, 'showPayment'])->name('payment');
Route::get('/pay', [PaymentController::class, 'pay'])->name('pay');
