<?php

use Codanux\MultiLanguage\Http\Controllers\CurrentTeamController;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\Livewire\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Livewire\TeamController;
use Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController;
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

                    // API...
                    if (Jetstream::hasApiFeatures()) {
                        Route::get(trans("routes.api-tokens.index", [], $locale), [ApiTokenController::class, 'index'])->name('api-tokens.index');
                    }

                    // Teams...
                    if (Jetstream::hasTeamFeatures()) {
                        Route::get(trans("routes.teams.create", [], $locale), [TeamController::class, 'create'])->name('teams.create');
                        Route::get(trans("routes.teams.show", [], $locale), [TeamController::class, 'show'])->name('teams.show');
                        Route::put(trans("routes.current-team.update", [], $locale), [CurrentTeamController::class, 'update'])->name('current-team.update');
                    }
                });
            });
        });
}

