<?php

use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\CurrentTeamController;
use Laravel\Jetstream\Http\Controllers\Inertia\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Inertia\CurrentUserController;
use Laravel\Jetstream\Http\Controllers\Inertia\OtherBrowserSessionsController;
use Laravel\Jetstream\Http\Controllers\Inertia\ProfilePhotoController;
use Laravel\Jetstream\Http\Controllers\Inertia\TeamController;
use Laravel\Jetstream\Http\Controllers\Inertia\TeamMemberController;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;
use Laravel\Jetstream\Jetstream;

foreach(config('multi-language.locales') as $locale) {
    Route::prefix($locale == config('multi-language.default_locale') ? null : $locale)
        ->name($locale . '.')
        ->group(function () use ($locale) {

            Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () use ($locale) {
                Route::group(['middleware' => ['auth', 'verified']], function () use ($locale) {
                    // User & Profile...
                    Route::get(trans("routes.profile.show", [], $locale), [UserProfileController::class, 'show'])
                        ->name('profile.show');

                    Route::delete(trans("routes.other-browser-sessions.destroy", [], $locale), [OtherBrowserSessionsController::class, 'destroy'])
                        ->name('other-browser-sessions.destroy');

                    Route::delete(trans("routes.current-user.destroy", [], $locale), [CurrentUserController::class, 'destroy'])
                        ->name('current-user.destroy');

                    Route::delete(trans("routes.current-user-photo.destroy", [], $locale), [ProfilePhotoController::class, 'destroy'])
                        ->name('current-user-photo.destroy');

                    // API...
                    if (Jetstream::hasApiFeatures()) {
                        Route::get(trans("routes.api-tokens.index", [], $locale), [ApiTokenController::class, 'index'])->name('api-tokens.index');
                        Route::post(trans("routes.api-tokens.store", [], $locale), [ApiTokenController::class, 'store'])->name('api-tokens.store');
                        Route::put(trans("routes.api-tokens.update", [], $locale), [ApiTokenController::class, 'update'])->name('api-tokens.update');
                        Route::delete(trans("routes.api-tokens.destroy", [], $locale), [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
                    }

                    // Teams...
                    if (Jetstream::hasTeamFeatures()) {
                        Route::get(trans("routes.teams.create", [], $locale), [TeamController::class, 'create'])->name('teams.create');
                        Route::post(trans("routes.teams.store", [], $locale), [TeamController::class, 'store'])->name('teams.store');
                        Route::get(trans("routes.teams.show", [], $locale), [TeamController::class, 'show'])->name('teams.show');
                        Route::put(trans("routes.teams.update", [], $locale), [TeamController::class, 'update'])->name('teams.update');
                        Route::delete(trans("routes.teams.destroy", [], $locale), [TeamController::class, 'destroy'])->name('teams.destroy');
                        Route::put(trans("routes.current-team.update", [], $locale), [CurrentTeamController::class, 'update'])->name('current-team.update');
                        Route::post(trans("routes.team-members.store", [], $locale), [TeamMemberController::class, 'store'])->name('team-members.store');
                        Route::put(trans("routes.team-members.update", [], $locale), [TeamMemberController::class, 'update'])->name('team-members.update');
                        Route::delete(trans("routes.team-members.destroy", [], $locale), [TeamMemberController::class, 'destroy'])->name('team-members.destroy');
                    }
                });
            });


        });
}
