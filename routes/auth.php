<?php

use App\Actions\Auth\AuthenticatedSessionAction;
use App\Actions\Auth\ConfirmablePasswordAction;
use App\Actions\Auth\EmailVerificationNotificationAction;
use App\Actions\Auth\EmailVerificationPromptAction;
use App\Actions\Auth\NewPasswordAction;
use App\Actions\Auth\PasswordAction;
use App\Actions\Auth\PasswordResetLinkAction;
use App\Actions\Auth\RegisteredUserAction;
use App\Actions\Auth\VerifyEmailAction;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisteredUserAction::class, 'create'])
                ->name("register");

    Route::get('login', [AuthenticatedSessionAction::class, 'create'])
                ->name('login');
    
    Route::post('register', [RegisteredUserAction::class, 'store']);

    Route::post('login', [AuthenticatedSessionAction::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkAction::class, 'create'])
                ->name('password.request');
    
    Route::post('forgot-password', [PasswordResetLinkAction::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordAction::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordAction::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {

    Route::get('verify-email', EmailVerificationPromptAction::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailAction::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationAction::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordAction::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordAction::class, 'store']);

    Route::put('password', [PasswordAction::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionAction::class, 'destroy'])
                ->name('logout');
});
