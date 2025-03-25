<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// Route::post('/register', [RegisteredUserController::class, 'store'])
//     ->middleware('guest')
//     ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    // ->middleware('guest')
    ->name('login');
Route::post('/mobile_verification', [AuthenticatedSessionController::class, 'mobile_verification'])
    // ->middleware('guest')
    ->name('mobile_verification');
Route::post('/mobile_login', [AuthenticatedSessionController::class, 'mobile_login'])
    // ->middleware('guest')
    ->name('mobile_login');
// Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//     ->middleware('guest')
//     ->name('password.email');

// Route::post('/reset-password', [NewPasswordController::class, 'store'])
//     ->middleware('guest')
//     ->name('password.update');

// Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//     ->middleware(['auth', 'signed', 'throttle:6,1'])
//     ->name('verification.verify');

// Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//     ->middleware(['auth', 'throttle:6,1'])
//     ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware(['auth:sanctum'])
    ->name('logout');


Route::get('/users', [AuthenticatedSessionController::class, 'user'])
    ->middleware('auth:sanctum')
    ->name('user2');
