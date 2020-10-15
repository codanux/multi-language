<?php

use Codanux\MultiLanguage\Http\Controllers\CurrentTeamController;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\Livewire\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Livewire\TeamController;
use Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController;
use Laravel\Jetstream\Jetstream;

Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        // User & Profile...
        Route::locale("profile.show", [UserProfileController::class, 'show'])
            ->name('profile.show');

        // API...
        if (Jetstream::hasApiFeatures()) {
            Route::locale("api-tokens.index", [ApiTokenController::class, 'index'])->name('api-tokens.index');
        }

        // Teams...
        if (Jetstream::hasTeamFeatures()) {
            Route::locale("teams.create", [TeamController::class, 'create'])->name('teams.create');
            Route::locale("teams.show", [TeamController::class, 'show'])->name('teams.show');
            Route::locale("current-team.edit", [CurrentTeamController::class, 'update'])->name('current-team.update')
            ->method('put');
        }
    });
});
