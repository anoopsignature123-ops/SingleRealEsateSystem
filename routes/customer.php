<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerPanle\CustomerAuthController;
use App\Http\Controllers\CustomerPanle\CustomerDashboardController;
use App\Http\Controllers\CustomerPanle\CustomerHistoryController;

Route::prefix('customer-panel')->name('customer-panel.')->group(function () {
    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', [CustomerAuthController::class, 'loginForm'])->name('login');
        Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
    });
    Route::middleware('auth:customer')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

        Route::get('/profile', [CustomerHistoryController::class, 'profile'])->name('profile');
        Route::get('/booking-history', [CustomerHistoryController::class, 'bookingHistory'])->name('booking-history');
        Route::get('/payment-history', [CustomerHistoryController::class, 'paymentHistory'])->name('payment-history');
        Route::get('/my-plot-booking', [CustomerHistoryController::class, 'myPlotBooking'])->name('my-plot-booking');
        Route::get('/support', [CustomerHistoryController::class, 'support'])->name('support');
        Route::post('/support', [CustomerHistoryController::class, 'supportStore'])->name('support.store');
    });
});
