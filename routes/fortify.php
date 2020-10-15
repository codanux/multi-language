<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;



Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    // Authentication...
    Route::locale("login", [AuthenticatedSessionController::class, 'create'])
        ->middleware(['guest'])
        ->name('login');

    $limiter = config('fortify.limiters.login');

    Route::locale("login", [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest',
            $limiter ? 'throttle:' . $limiter : null,
        ]))->method('post');

    Route::locale("logout", [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout')
        ->method('post');;

    // Password Reset...
    if (Features::enabled(Features::resetPasswords())) {
        Route::locale("password.request", [PasswordResetLinkController::class, 'create'])
            ->middleware(['guest'])
            ->name('password.request');

        Route::locale("password.email", [PasswordResetLinkController::class, 'store'])
            ->middleware(['guest'])
            ->name('password.email')
            ->method('post');

        Route::locale("password.reset", [NewPasswordController::class, 'create'])
            ->middleware(['guest'])
            ->name('password.reset');

        Route::locale("password.edit", [NewPasswordController::class, 'store'])
            ->middleware(['guest'])
            ->name('password.update')
            ->method('post');
    }

    // Registration...
    if (Features::enabled(Features::registration())) {
        Route::locale("register", [RegisteredUserController::class, 'create'])
            ->middleware(['guest'])
            ->name('register');

        Route::locale("register", [RegisteredUserController::class, 'store'])
            ->middleware(['guest'])
            ->method('post');
    }

    // Email Verification...
    if (Features::enabled(Features::emailVerification())) {
        Route::locale("verification.notice", [EmailVerificationPromptController::class, '__invoke'])
            ->middleware(['auth'])
            ->name('verification.notice');

        Route::locale("verification.verify", [VerifyEmailController::class, '__invoke'])
            ->middleware(['auth', 'signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::locale("verification.send", [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['auth', 'throttle:6,1'])
            ->name('verification.send')
            ->method('post');
    }

    // Profile Information...
    if (Features::enabled(Features::updateProfileInformation())) {
        Route::locale("user-profile-information.update", [ProfileInformationController::class, 'update'])
            ->middleware(['auth'])
            ->name('user-profile-information.update')
            ->method('put');;
    }

    // Passwords...
    if (Features::enabled(Features::updatePasswords())) {
        Route::locale("user-password.edit", [PasswordController::class, 'update'])
            ->middleware(['auth'])
            ->name('user-password.update')
            ->method('put');;
    }

    // Password Confirmation...
    Route::locale("password.confirm", [ConfirmablePasswordController::class, 'show'])
        ->middleware(['auth'])
        ->name('password.confirm');

    Route::locale("password.confirm", [ConfirmablePasswordController::class, 'store'])
        ->middleware(['auth'])
        ->method('post');;

    Route::locale("password.confirmation", [ConfirmedPasswordStatusController::class, 'show'])
        ->middleware(['auth'])
        ->name('password.confirmation');

    // Two Factor Authentication...
    if (Features::enabled(Features::twoFactorAuthentication())) {
        Route::locale("two-factor.login", [TwoFactorAuthenticatedSessionController::class, 'create'])
            ->middleware(['guest'])
            ->name('two-factor.login');

        Route::locale("two-factor.login", [TwoFactorAuthenticatedSessionController::class, 'store'])
            ->middleware(['guest'])
            ->method('post');;

        $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
            ? ['auth', 'password.confirm']
            : ['auth'];

        Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
            ->middleware($twoFactorMiddleware);

        Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
            ->middleware($twoFactorMiddleware);

        Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
            ->middleware($twoFactorMiddleware);

        Route::get('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
            ->middleware($twoFactorMiddleware);

        Route::post('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
            ->middleware($twoFactorMiddleware);
    }
});
