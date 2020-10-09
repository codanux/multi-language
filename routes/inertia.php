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


Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        // User & Profile...
        Route::locale("profile.show", [UserProfileController::class, 'show'])
            ->name('profile.show');

        Route::locale("other-browser-sessions.destroy", [OtherBrowserSessionsController::class, 'destroy'])
            ->name('other-browser-sessions.destroy')
            ->method("delete");

        Route::locale("current-user.destroy", [CurrentUserController::class, 'destroy'])
            ->name('current-user.destroy')
            ->method("delete");

        Route::locale("current-user-photo.destroy", [ProfilePhotoController::class, 'destroy'])
            ->name('current-user-photo.destroy')
            ->method("delete");

        // API...
        if (Jetstream::hasApiFeatures()) {
            Route::locale("api-tokens.index", [ApiTokenController::class, 'index'])->name('api-tokens.index');
            Route::locale("api-tokens.store", [ApiTokenController::class, 'store'])->name('api-tokens.store')->method("post");
            Route::locale("api-tokens.update", [ApiTokenController::class, 'update'])->name('api-tokens.update')->method("put");
            Route::locale("api-tokens.destroy", [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy')->method("delete");
        }

        // Teams...
        if (Jetstream::hasTeamFeatures()) {
            Route::locale("teams.create",  [TeamController::class, 'create'])->name('teams.create');
            Route::locale("teams.store",  [TeamController::class, 'store'])->name('teams.store')->method("post");
            Route::locale("teams.show",  [TeamController::class, 'show'])->name('teams.show');
            Route::locale("teams.update",  [TeamController::class, 'update'])->name('teams.update')->method("put");
            Route::locale("teams.destroy",  [TeamController::class, 'destroy'])->name('teams.destroy')->method("delete");
            Route::locale("current-team.update",  [CurrentTeamController::class, 'update'])->name('current-team.update')->method("put");
            Route::locale("team-members.store",  [TeamMemberController::class, 'store'])->name('team-members.store')->method("post");
            Route::locale("team-members.update",  [TeamMemberController::class, 'update'])->name('team-members.update')->method("put");
            Route::locale("team-members.destroy",  [TeamMemberController::class, 'destroy'])->name('team-members.destroy')->method("delete");
        }
    });
});
