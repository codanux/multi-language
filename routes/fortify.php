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


foreach(config('multi-language.locales') as $locale)
{
    Route::prefix($locale == config('multi-language.default_locale') ? null : $locale)
        ->name($locale.'.')
        ->group(function() use($locale) {

        Route::group(['middleware' => config('fortify.middleware', ['web'])], function () use ($locale) {
            // Authentication...
            Route::get(trans("routes.login", [], $locale), [AuthenticatedSessionController::class, 'create'])
                ->middleware(['guest'])
                ->name('login');

            $limiter = config('fortify.limiters.login');

            Route::post(trans("routes.login", [], $locale), [AuthenticatedSessionController::class, 'store'])
                ->middleware(array_filter([
                    'guest',
                    $limiter ? 'throttle:' . $limiter : null,
                ]));

            Route::post(trans("routes.logout", [], $locale), [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

            // Password Reset...
            if (Features::enabled(Features::resetPasswords())) {
                Route::get(trans("routes.password.request", [], $locale), [PasswordResetLinkController::class, 'create'])
                    ->middleware(['guest'])
                    ->name('password.request');

                Route::post(trans("routes.password.email", [], $locale), [PasswordResetLinkController::class, 'store'])
                    ->middleware(['guest'])
                    ->name('password.email');

                Route::get(trans("routes.password.reset", [], $locale), [NewPasswordController::class, 'create'])
                    ->middleware(['guest'])
                    ->name('password.reset');

                Route::post(trans("routes.password.update", [], $locale), [NewPasswordController::class, 'store'])
                    ->middleware(['guest'])
                    ->name('password.update');
            }

            // Registration...
            if (Features::enabled(Features::registration())) {
                Route::get(trans("routes.register", [], $locale), [RegisteredUserController::class, 'create'])
                    ->middleware(['guest'])
                    ->name('register');

                Route::post(trans("routes.register", [], $locale), [RegisteredUserController::class, 'store'])
                    ->middleware(['guest']);
            }

            // Email Verification...
            if (Features::enabled(Features::emailVerification())) {
                Route::get(trans("routes.verification.notice", [], $locale), [EmailVerificationPromptController::class, '__invoke'])
                    ->middleware(['auth'])
                    ->name('verification.notice');

                Route::get(trans("routes.verification.verify", [], $locale), [VerifyEmailController::class, '__invoke'])
                    ->middleware(['auth', 'signed', 'throttle:6,1'])
                    ->name('verification.verify');

                Route::post(trans("routes.verification.send", [], $locale), [EmailVerificationNotificationController::class, 'store'])
                    ->middleware(['auth', 'throttle:6,1'])
                    ->name('verification.send');
            }

            // Profile Information...
            if (Features::enabled(Features::updateProfileInformation())) {
                Route::put(trans("routes.user-profile-information.update", [], $locale), [ProfileInformationController::class, 'update'])
                    ->middleware(['auth'])
                    ->name('user-profile-information.update');
            }

            // Passwords...
            if (Features::enabled(Features::updatePasswords())) {
                Route::put(trans("routes.user-password.update", [], $locale), [PasswordController::class, 'update'])
                    ->middleware(['auth'])
                    ->name('user-password.update');
            }

            // Password Confirmation...
            Route::get(trans("routes.password.confirm", [], $locale), [ConfirmablePasswordController::class, 'show'])
                ->middleware(['auth'])
                ->name('password.confirm');

            Route::post(trans("routes.password.confirm", [], $locale), [ConfirmablePasswordController::class, 'store'])
                ->middleware(['auth']);

            Route::get(trans("routes.password.confirmation", [], $locale), [ConfirmedPasswordStatusController::class, 'show'])
                ->middleware(['auth'])
                ->name('password.confirmation');

            // Two Factor Authentication...
            if (Features::enabled(Features::twoFactorAuthentication())) {
                Route::get(trans("routes.two-factor.login", [], $locale), [TwoFactorAuthenticatedSessionController::class, 'create'])
                    ->middleware(['guest'])
                    ->name('two-factor.login');

                Route::post(trans("routes.two-factor.login", [], $locale), [TwoFactorAuthenticatedSessionController::class, 'store'])
                    ->middleware(['guest']);

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

    });
}
