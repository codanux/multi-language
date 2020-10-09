<?php

return [
    'welcome' => '',
    'dashboard' => 'dashboard',

    'login' => 'login',
    'logout' => 'logout',
    'register' => 'register',

    'password' => [
        'request' => 'forgot-password',
        'email' => 'forgot-password',
        'reset' => 'reset-password/{token}',
        'update' => 'reset-password',
        'confirm' => 'user/confirm-password',
        'confirmation' => 'user/confirmed-password-status',
    ],

    'verification' => [
        'notice' => 'email/verify',
        'verify' => 'email/verify/{id}/{hash}',
        'send' => 'email/verification-notification',
    ],

    'two-factor' => [
        'login' => 'two-factor-challenge',
    ],


    'profile' => [
        'show' => 'user/profile',
    ],
    'api-tokens' => [
        'index' => 'user/api-tokens',
        'store' => 'user/api-tokens',
        'update' => 'user/api-tokens/{token}',
        'destroy' => 'user/api-tokens/{token}',
    ],
    'teams' => [
        'create' => 'teams/create',
        'store' => 'teams',
        'show' => 'teams/{team}',
        'update' => 'teams/{team}',
        'destroy' => 'teams/{team}',
    ],

    'team-members' => [
        'store' => 'teams/{team}/members',
        'update' => 'teams/{team}/members/{user}',
        'destroy' => 'teams/{team}/members/{user}',
    ],

    'current-team' => [
        'update' => 'current-team',
    ],

    'other-browser-sessions' => [
        'destroy' => 'user/other-browser-sessions',
    ],

    'current-user' => [
        'destroy' => 'user'
    ],

    'current-user-photo' => [
        'destroy' => 'user/profile-photo',

    ],

    'post' => [
        'index' => 'posts',
        'create' => 'posts/create',
        'show' => 'posts/{post}',
        'edit' => 'posts/{post}/edit',
        'destroy' => 'posts/{post}/delete',
    ],
];
