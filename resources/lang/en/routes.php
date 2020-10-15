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
        'edit' => 'reset-password',
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
        'create' => 'user/api-tokens',
        'edit' => 'user/api-tokens/{token}',
        'destroy' => 'user/api-tokens/{token}',
    ],
    'teams' => [
        'create' => 'teams/create',
        'show' => 'teams/{team}',
        'edit' => 'teams/{team}',
        'destroy' => 'teams/{team}',
    ],

    'team-members' => [
        'create' => 'teams/{team}/members',
        'edit' => 'teams/{team}/members/{user}',
        'destroy' => 'teams/{team}/members/{user}',
    ],

    'current-team' => [
        'edit' => 'current-team',
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
